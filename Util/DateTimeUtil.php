<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) Sandro Groganz <sandro@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\CoreBundle\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DateTimeUtil
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getNow($timezone = 'UTC'){
        return new \DateTime('now', new \DateTimeZone($timezone));
    }

    public function setUserTimezone(\DateTime $dateTime){
        return $dateTime->setTimezone(new \DateTimeZone($this->container->get('session')->get('campaignchain.timezone')));
    }

    public function isUserTimezone(\DateTime $dateTime){
        return $dateTime->getTimezone()->getName() == $this->container->get('session')->get('campaignchain.timezone');
    }

    /*
     * A Symfony form sends datetime back in UTC timezone,
     * because no timezone string has been provided with the posted data.
     */
    public function modifyTimezoneOffset(\DateTime $dateTime){
        $userTimezone = new \DateTimeZone($this->container->get('session')->get('campaignchain.timezone'));
        $offset = $userTimezone->getOffset($dateTime);
        return $dateTime->modify('-'.$offset.' sec');
    }

    public function getUserLocale(){
        return $this->container->get('session')->get('campaignchain.locale');
    }

    public function getUserTimezone(){
        return $this->container->get('session')->get('campaignchain.timezone');
    }

    public function getUserDatetimeFormat($format = 'default'){
        switch($format){
            case 'moment_js':
                return $this->convertToMomentJSFormat($this->container->get('session')->get('campaignchain.dateFormat').' '.$this->container->get('session')->get('campaignchain.timeFormat'));
                break;
            case 'datepicker':
                return $this->convertToDatepickerFormat($this->container->get('session')->get('campaignchain.dateFormat').' '.$this->container->get('session')->get('campaignchain.timeFormat'));
                break;
            default:
                return $this->container->get('session')->get('campaignchain.dateFormat').' '.$this->container->get('session')->get('campaignchain.timeFormat');
                break;
        }
    }

    public function getUserDateFormat(){
        return $this->container->get('session')->get('campaignchain.dateFormat');
    }

    public function getUserTimeFormat(){
        return $this->container->get('session')->get('campaignchain.timeFormat');
    }

    static function roundMinutes($datetime){
        // 1) Set number of seconds to 0 (by rounding up to the nearest minute).
        $second = $datetime->format("s");
        $datetime->add(new \DateInterval("PT".(60-$second)."S"));
        // 2) Round to 5 minute increment.
        $minutes = (round($datetime->format("i")/5) * 5) % 60;
        // 3) Set rounded minutes.
        $datetime->setTime($datetime->format('H'), $minutes, 0);

        return $datetime;
    }

    static function isWithinDuration(\DateTime $start, \DateTime $moment, \DateTime $end){
        // Set all dates to UTC to normalize timezone.
        $utc = new \DateTimeZone('UTC');
        $start->setTimezone($utc);
        $moment->setTimezone($utc);
        $end->setTimezone($utc);

        if($moment > $start && $moment < $end){
            return true;
        }

        return false;
    }

    public function formatLocale(\DateTime $object, $format = null){
        switch($format){
            case 'ISO8601':
                $object->setTimezone(
                    new \DateTimeZone($this->container->get('session')->get('campaignchain.timezone'))
                );
                return $object->format(\DateTime::ISO8601);
                break;
            default:
                // Apply timezone and locale to DateTime object
                $localeFormat = new \IntlDateFormatter(
                    $this->container->get('session')->get('campaignchain.locale'),
                    \IntlDateFormatter::FULL,
                    \IntlDateFormatter::FULL,
                    $this->container->get('session')->get('campaignchain.timezone')
                );
                $localeFormat->setPattern($this->container->get('session')->get('campaignchain.dateFormat').' '.$this->container->get('session')->get('campaignchain.timeFormat'));
                return $localeFormat->format($object);
                break;
        }
    }

    private function convertToMomentJSFormat($format){
        // moment.js re-formatting
        $patterns[] = '/d/';
        $patterns[] = '/yyyy/';
        $patterns[] = '/EEE/';
        $patterns[] = '/EEEE/';

        $replacements[] = 'D';
        $replacements[] = 'YYYY';
        $replacements[] = 'ddd';
        $replacements[] = 'dddd';

        return preg_replace($patterns, $replacements, $format);
    }

    private function convertToDatepickerFormat($format){
        // moment.js re-formatting
        $patterns[] = '/m/';
        $patterns[] = '/mm/';
        $patterns[] = '/M/';
        $patterns[] = '/MM/';

        $replacements[] = 'i';
        $replacements[] = 'ii';
        $replacements[] = 'm';
        $replacements[] = 'mm';

        return preg_replace($patterns, $replacements, $format);
    }
}