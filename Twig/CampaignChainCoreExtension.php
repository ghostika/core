<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) Sandro Groganz <sandro@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class CampaignChainCoreExtension extends \Twig_Extension
{
    protected $em;
    protected $container;
    protected $datetime;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->datetime = $this->container->get('campaignchain.core.util.datetime');
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('campaignchain_image', array($this, 'image')),
            new \Twig_SimpleFilter('campaignchain_channel_asset_path', array($this, 'channelAssetPath')),
            new \Twig_SimpleFilter('campaignchain_channel_icon_name', array($this, 'channelIconName')),
            new \Twig_SimpleFilter('campaignchain_datetime', array($this, 'datetime')),
            new \Twig_SimpleFilter('campaignchain_timezone', array($this, 'timezone')),
            new \Twig_SimpleFilter('campaignchain_data_trigger_hook', array($this, 'dataTriggerHook')),
            new \Twig_SimpleFilter('campaignchain_tpl_trigger_hook_inline', array($this, 'tplTriggerHookInline')),
        );
    }

    public function system(){
        return $this->em->getRepository('CampaignChainCoreBundle:System')->find(1);
    }

    public function image($object)
    {
        $class = get_class($object);

        if(strpos($class, 'CoreBundle\Entity\Location') !== false){
            return $object->getImage();
        } else {
            return $this->channelAssetPath($object).'/images/icons/16x16/'.$this->channelIconName($object);
        }
    }

    public function channelAssetPath($object)
    {
        $class = get_class($object);

        if(strpos($class, 'CoreBundle\Entity\Bundle') !== false){
            $bundlePath = $object->getPath();
        } elseif(strpos($class, 'CoreBundle\Entity\ChannelModule') !== false){
            $bundlePath = $object->getBundle()->getPath();
        } elseif(strpos($class, 'CoreBundle\Entity\Channel') !== false){
            $bundlePath = $object->getChannelModule()->getBundle()->getPath();
        } elseif(strpos($class, 'CoreBundle\Entity\Activity') !== false){
            $bundlePath = $object->getChannel()->getChannelModule()->getBundle()->getPath();
        }

        $path = 'bundles/campaignchain'.strtolower(str_replace(DIRECTORY_SEPARATOR, '', str_replace('Bundle', '', $bundlePath)));

        return $path;
    }

    public function channelIconName($object)
    {
        $class = get_class($object);

        if(strpos($class, 'CoreBundle\Entity\Bundle') !== false){
            $bundleName = $object->getName();
        } elseif(strpos($class, 'CoreBundle\Entity\ChannelModule') !== false){
            $bundleName = $object->getBundle()->getName();
        } elseif(strpos($class, 'CoreBundle\Entity\Channel') !== false){
            $bundleName = $object->getChannelModule()->getBundle()->getName();
        } elseif(strpos($class, 'CoreBundle\Entity\Activity') !== false){
            $bundleName = $object->getChannel()->getChannelModule()->getBundle()->getName();
        }

        $iconName = str_replace('campaignchain/channel-', '', $bundleName).'.png';

        return $iconName;
    }

    public function datetime($object, $format = null){
        if($object instanceof \DateTime){
            $datetimeUtil = $this->container->get('campaignchain.core.util.datetime');
            return $datetimeUtil->formatLocale($object, $format);
        } else {
            // TODO: Throw error.
        }
    }

    public function timezone($object){
        return $object->setTimezone(new \DateTimeZone($this->container->get('session')->get('campaignchain.timezone')));
    }

    public function dataTriggerHook($object)
    {
        $hookConfig = $this->em->getRepository('CampaignChainCoreBundle:Hook')->find($object->getTriggerHook());
        $hookService = $this->container->get($hookConfig->getServices()['entity']);
        $hookData = $hookService->getHook($object);

        return $hookData;
    }

    public function tplTriggerHookInline($object)
    {
        // TODO: Store already retrieved service string in a property of this class for performance reasons.
        $hookConfig = $this->em->getRepository('CampaignChainCoreBundle:Hook')->find($object->getTriggerHook());
        $hookService = $this->container->get($hookConfig->getServices()['entity']);
        return $hookService->tplInline($object);
    }

    public function getGlobals()
    {
        return array(
            "campaignchain_user_datetime_format" => array(
                'moment_js' => $this->datetime->getUserDatetimeFormat('moment_js'),
                'iso8601' => $this->container->get('session')->get('campaignchain.dateFormat').' '.$this->container->get('session')->get('campaignchain.timeFormat'),
            ),
            "campaignchain_user_timezone_offset" => $this->getGlobalTimezoneOffset(),
            "campaignchain_user_timezone_abbreviation" => $this->getGlobalTimezoneAbbreviation(),
            'campaignchain_system' => $this->system(),
        );
    }

    private function getGlobalTimezoneOffset(){
        // Execute only if the user is logged in.
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            $timezoneUser = new \DateTimeZone($this->container->get('session')->get('campaignchain.timezone'));
            $timezoneUTC = new \DateTimeZone("UTC");
    //
            $dateUser = new \DateTime("now", $timezoneUser);
            $dateUTC = new \DateTime("now", $timezoneUTC);

            $offset = $timezoneUser->getOffset($dateUTC);

            $offsetHours = round(abs($offset)/3600);
            $offsetMinutes = round((abs($offset) - $offsetHours * 3600) / 60);
            $offsetString = ($offset < 0 ? '-' : '+')
                . ($offsetHours < 10 ? '0' : '') . $offsetHours
                . ':'
                . ($offsetMinutes < 10 ? '0' : '') . $offsetMinutes;

            return $offsetString;
        } else {
            return '+00:00';
        }
    }

    private function getGlobalTimezoneAbbreviation(){
        // Execute only if the user is logged in.
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            $timezoneUser = new \DateTimeZone($this->container->get('session')->get('campaignchain.timezone'));
            $dateUser = new \DateTime("now", $timezoneUser);
            return $dateUser->format('T');
        } else {
            return 'UTC';
        }
    }

    public function getName()
    {
        return 'campaignchain_core_extension';
    }
}
