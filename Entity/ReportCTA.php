<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) Sandro Groganz <sandro@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Call to Action (CTA)
 *
 * @ORM\Entity
 * @ORM\Table(name="campaignchain_report_cta")
 * @ORM\HasLifecycleCallbacks
 */
class ReportCTA
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CTA")
     * @ORM\JoinColumn(name="cta_id", referencedColumnName="id", nullable=false)
     */
    protected $CTA;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="referrer_location_id", referencedColumnName="id", nullable=false)
     */
    protected $referrerLocation;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $referrerUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $referrerName;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="source_location_id", referencedColumnName="id", nullable=false)
     */
    protected $sourceLocation;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $sourceUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $sourceName;

    /**
     * @ORM\ManyToOne(targetEntity="Location", cascade={"persist"})
     * @ORM\JoinColumn(name="target_location_id", referencedColumnName="id", nullable=true)
     */
    protected $targetLocation;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $targetUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $targetName;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function setTime()
    {
        $this->time = new \DateTime('now');
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set CTA
     *
     * @param \CampaignChain\CoreBundle\Entity\CTA $CTA
     * @return ReportCTA
     */
    public function setCTA(\CampaignChain\CoreBundle\Entity\CTA $CTA)
    {
        $this->CTA = $CTA;

        return $this;
    }

    /**
     * Get CTA
     *
     * @return \CampaignChain\CoreBundle\Entity\CTA
     */
    public function getCTA()
    {
        return $this->CTA;
    }

    /**
     * Set sourceUrl
     *
     * @param string $sourceUrl
     * @return ReportCTA
     */
    public function setSourceUrl($sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    /**
     * Get sourceUrl
     *
     * @return string 
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * Set sourceName
     *
     * @param string $sourceName
     * @return ReportCTA
     */
    public function setSourceName($sourceName)
    {
        $this->sourceName = $sourceName;

        return $this;
    }

    /**
     * Get sourceName
     *
     * @return string 
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * Set targetUrl
     *
     * @param string $targetUrl
     * @return ReportCTA
     */
    public function setTargetUrl($targetUrl)
    {
        $this->targetUrl = $targetUrl;

        return $this;
    }

    /**
     * Get targetUrl
     *
     * @return string 
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * Set targetName
     *
     * @param string $targetName
     * @return ReportCTA
     */
    public function setTargetName($targetName)
    {
        $this->targetName = $targetName;

        return $this;
    }

    /**
     * Get targetName
     *
     * @return string 
     */
    public function getTargetName()
    {
        return $this->targetName;
    }

    /**
     * Set sourceLocation
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $sourceLocation
     * @return ReportCTA
     */
    public function setSourceLocation(\CampaignChain\CoreBundle\Entity\Location $sourceLocation)
    {
        $this->sourceLocation = $sourceLocation;

        return $this;
    }

    /**
     * Get sourceLocation
     *
     * @return \CampaignChain\CoreBundle\Entity\Location
     */
    public function getSourceLocation()
    {
        return $this->sourceLocation;
    }

    /**
     * Set targetLocation
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $targetLocation
     * @return ReportCTA
     */
    public function setTargetLocation(\CampaignChain\CoreBundle\Entity\Location $targetLocation = null)
    {
        $this->targetLocation = $targetLocation;

        return $this;
    }

    /**
     * Get targetLocation
     *
     * @return \CampaignChain\CoreBundle\Entity\Location
     */
    public function getTargetLocation()
    {
        return $this->targetLocation;
    }

    /**
     * Set referrerUrl
     *
     * @param string $referrerUrl
     * @return ReportCTA
     */
    public function setReferrerUrl($referrerUrl)
    {
        $this->referrerUrl = $referrerUrl;

        return $this;
    }

    /**
     * Get referrerUrl
     *
     * @return string 
     */
    public function getReferrerUrl()
    {
        return $this->referrerUrl;
    }

    /**
     * Set referrerName
     *
     * @param string $referrerName
     * @return ReportCTA
     */
    public function setReferrerName($referrerName)
    {
        $this->referrerName = $referrerName;

        return $this;
    }

    /**
     * Get referrerName
     *
     * @return string 
     */
    public function getReferrerName()
    {
        return $this->referrerName;
    }

    /**
     * Set referrerLocation
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $referrerLocation
     * @return ReportCTA
     */
    public function setReferrerLocation(\CampaignChain\CoreBundle\Entity\Location $referrerLocation)
    {
        $this->referrerLocation = $referrerLocation;

        return $this;
    }

    /**
     * Get referrerLocation
     *
     * @return \CampaignChain\CoreBundle\Entity\Location
     */
    public function getReferrerLocation()
    {
        return $this->referrerLocation;
    }
}
