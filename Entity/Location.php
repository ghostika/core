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
 * @ORM\Entity
 * @ORM\Table(name="campaignchain_location")
 */
class Location extends Medium
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="LocationModule", cascade={"merge"})
     */
    protected $locationModule;

    /**
     * @ORM\ManyToOne(targetEntity="Channel")
     */
    protected $channel;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="location")
     */
    protected $activities;

    /**
     * @ORM\ManyToOne(targetEntity="Operation", cascade={"persist"})
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id", nullable=true)
     */
    protected $operation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $identifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    protected $url;

    /**
     * @ORM\OneToMany(targetEntity="CTA", mappedBy="location")
     */
    protected $ctas;

    /**
     * @ORM\OneToMany(targetEntity="ReportCTA", mappedBy="referrerLocation")
     */
    protected $referrerCtas;

    /**
     * @ORM\OneToMany(targetEntity="ReportCTA", mappedBy="sourceLocation")
     */
    protected $sourceCtas;

    /**
     * @ORM\OneToMany(targetEntity="ReportCTA", mappedBy="targetLocation")
     */
    protected $targetCtas;

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
     * Set locationModule
     *
     * @param \CampaignChain\CoreBundle\Entity\LocationModule $locationModule
     * @return Location
     */
    public function setLocationModule(\CampaignChain\CoreBundle\Entity\LocationModule $locationModule = null)
    {
        $this->locationModule = $locationModule;

        return $this;
    }

    /**
     * Get locationModule
     *
     * @return \CampaignChain\CoreBundle\Entity\LocationModule
     */
    public function getLocationModule()
    {
        return $this->locationModule;
    }

    /**
     * Convenience method that masquerades getLocationModule()
     *
     * @return \CampaignChain\CoreBundle\Entity\LocationModule
     */
    public function getModule()
    {
        return $this->locationModule;
    }

    /**
     * Set channel
     *
     * @param \CampaignChain\CoreBundle\Entity\Channel $channel
     * @return Location
     */
    public function setChannel(\CampaignChain\CoreBundle\Entity\Channel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \CampaignChain\CoreBundle\Entity\Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Add activities
     *
     * @param \CampaignChain\CoreBundle\Entity\Activity $activities
     * @return Location
     */
    public function addActivity(\CampaignChain\CoreBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \CampaignChain\CoreBundle\Entity\Activity $activities
     */
    public function removeActivity(\CampaignChain\CoreBundle\Entity\Activity $activities)
    {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Location
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return Location
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set URL
     *
     * @param string $url
     * @return Location
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set operation
     *
     * @param \CampaignChain\CoreBundle\Entity\Operation $operation
     * @return Location
     */
    public function setOperation(\CampaignChain\CoreBundle\Entity\Operation $operation = null)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return \CampaignChain\CoreBundle\Entity\Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Add ctas
     *
     * @param \CampaignChain\CoreBundle\Entity\CTA $ctas
     * @return Location
     */
    public function addCta(\CampaignChain\CoreBundle\Entity\CTA $ctas)
    {
        $this->ctas[] = $ctas;

        return $this;
    }

    /**
     * Remove ctas
     *
     * @param \CampaignChain\CoreBundle\Entity\CTA $ctas
     */
    public function removeCta(\CampaignChain\CoreBundle\Entity\CTA $ctas)
    {
        $this->ctas->removeElement($ctas);
    }

    /**
     * Get ctas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCtas()
    {
        return $this->ctas;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ctas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->referrerCtas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sourceCtas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->targetCtas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add referrerCtas
     *
     * @param \CampaignChain\CoreBundle\Entity\ReportCTA $referrerCtas
     * @return Location
     */
    public function addReferrerCta(\CampaignChain\CoreBundle\Entity\ReportCTA $referrerCtas)
    {
        $this->referrerCtas[] = $referrerCtas;

        return $this;
    }

    /**
     * Remove referrerCtas
     *
     * @param \CampaignChain\CoreBundle\Entity\ReportCTA $referrerCtas
     */
    public function removeReferrerCta(\CampaignChain\CoreBundle\Entity\ReportCTA $referrerCtas)
    {
        $this->referrerCtas->removeElement($referrerCtas);
    }

    /**
     * Get referrerCtas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferrerCtas()
    {
        return $this->referrerCtas;
    }

    /**
     * Add sourceCtas
     *
     * @param \CampaignChain\CoreBundle\Entity\ReportCTA $sourceCtas
     * @return Location
     */
    public function addSourceCta(\CampaignChain\CoreBundle\Entity\ReportCTA $sourceCtas)
    {
        $this->sourceCtas[] = $sourceCtas;

        return $this;
    }

    /**
     * Remove sourceCtas
     *
     * @param \CampaignChain\CoreBundle\Entity\ReportCTA $sourceCtas
     */
    public function removeSourceCta(\CampaignChain\CoreBundle\Entity\ReportCTA $sourceCtas)
    {
        $this->sourceCtas->removeElement($sourceCtas);
    }

    /**
     * Get sourceCtas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSourceCtas()
    {
        return $this->sourceCtas;
    }

    /**
     * Add targetCtas
     *
     * @param \CampaignChain\CoreBundle\Entity\ReportCTA $targetCtas
     * @return Location
     */
    public function addTargetCta(\CampaignChain\CoreBundle\Entity\ReportCTA $targetCtas)
    {
        $this->targetCtas[] = $targetCtas;

        return $this;
    }

    /**
     * Remove targetCtas
     *
     * @param \CampaignChain\CoreBundle\Entity\ReportCTA $targetCtas
     */
    public function removeTargetCta(\CampaignChain\CoreBundle\Entity\ReportCTA $targetCtas)
    {
        $this->targetCtas->removeElement($targetCtas);
    }

    /**
     * Get targetCtas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTargetCtas()
    {
        return $this->targetCtas;
    }

    /**
     * Add children
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $children
     * @return Location
     */
    public function addChild(\CampaignChain\CoreBundle\Entity\Location $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $children
     */
    public function removeChild(\CampaignChain\CoreBundle\Entity\Location $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $parent
     * @return Location
     */
    public function setParent(\CampaignChain\CoreBundle\Entity\Location $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \CampaignChain\CoreBundle\Entity\Location
     */
    public function getParent()
    {
        return $this->parent;
    }
}
