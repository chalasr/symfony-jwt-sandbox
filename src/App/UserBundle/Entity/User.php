<?php

namespace App\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;
use JMS\Serializer\Annotation as JMS;

/**
 * User.
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    protected $facebookId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\Information\ProviderInformation", cascade={"persist"})
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     */
    protected $providerInformation;

    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\Information\CoachInformation", cascade={"persist"})
     * @ORM\JoinColumn(name="coach_id", referencedColumnName="id")
     */
    protected $coachInformation;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getFacebookId()
    {
        return $this->facebookId;
    }

    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this->facebookId;
    }

    /**
     * Set providerInformation
     *
     * @param \App\UserBundle\Entity\Information\ProviderInformation $providerInformation
     *
     * @return User
     */
    public function setProviderInformation(\App\UserBundle\Entity\Information\ProviderInformation $providerInformation = null)
    {
        $this->providerInformation = $providerInformation;

        return $this;
    }

    /**
     * Get providerInformation
     *
     * @return \App\UserBundle\Entity\Information\ProviderInformation
     */
    public function getProviderInformation()
    {
        return $this->providerInformation;
    }

    /**
     * Set coachInformation
     *
     * @param \App\UserBundle\Entity\Information\CoachInformation $coachInformation
     *
     * @return User
     */
    public function setCoachInformation(\App\UserBundle\Entity\Information\CoachInformation $coachInformation = null)
    {
        $this->coachInformation = $coachInformation;

        return $this;
    }

    /**
     * Get coachInformation
     *
     * @return \App\UserBundle\Entity\Information\CoachInformation
     */
    public function getCoachInformation()
    {
        return $this->coachInformation;
    }
}
