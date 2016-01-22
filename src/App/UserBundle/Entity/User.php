<?php

namespace App\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sonata\UserBundle\Model\User as BaseUser;
use Sonata\UserBundle\Model\UserInterface;

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
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="integer", nullable=true)
     */
    protected $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=true)
     */
    protected $dateOfBirth;

     /**
      * @ORM\ManyToOne(targetEntity="Group")
      * @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
      */
    protected $group;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="follows")
     * @ORM\JoinTable(
     * 			name="follows",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="follower_id", referencedColumnName="id")}
     * )
     */
    protected $followers;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="followers")
     */
    protected $follows;

    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\Information\ProviderInformation", cascade={"persist"})
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id", nullable=true)
     */
    protected $providerInformation;

    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\Information\CoachInformation", cascade={"persist"})
     * @ORM\JoinColumn(name="coach_id", referencedColumnName="id", nullable=true)
     */
    protected $coachInformation;

    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getEmail() ?: '-';
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->followers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->follows = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setEmail($email)
    {
         parent::setEmail($email);
         $this->setUsername($email);
    }

    /**
     * Get facebookId.
     *
     * @return int
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set facebookId .
     *
     * @param int $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Set providerInformation.
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
     * Get providerInformation.
     *
     * @return \App\UserBundle\Entity\Information\ProviderInformation
     */
    public function getProviderInformation()
    {
        return $this->providerInformation;
    }

    /**
     * Set coachInformation.
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
     * Get coachInformation.
     *
     * @return \App\UserBundle\Entity\Information\CoachInformation
     */
    public function getCoachInformation()
    {
        return $this->coachInformation;
    }

    /**
     * Add follower.
     *
     * @param \App\UserBundle\Entity\User $follower
     *
     * @return User
     */
    public function addFollower(\App\UserBundle\Entity\User $follower)
    {
        $this->followers[] = $follower;

        if (!$follower->getFollows()->contains($this)) {
            $follower->addFollow($this);
        }

        return $this;
    }

    /**
     * Remove follower.
     *
     * @param \App\UserBundle\Entity\User $follower
     */
    public function removeFollower(\App\UserBundle\Entity\User $follower)
    {
        $this->followers->removeElement($follower);

        if ($follower->getFollows()->contains($this)) {
            $follower->removeFollow($this);
        }
    }

    /**
     * Get followers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Add follow.
     *
     * @param \App\UserBundle\Entity\User $follow
     *
     * @return User
     */
    public function addFollow(\App\UserBundle\Entity\User $follow)
    {
        $this->follows[] = $follow;

        if (!$follow->getFollowers()->contains($this)) {
            $follow->addFollower($this);
        }

        return $this;
    }

    /**
     * Remove follow.
     *
     * @param \App\UserBundle\Entity\User $follow
     */
    public function removeFollow(\App\UserBundle\Entity\User $follow)
    {
        $this->follows->removeElement($follow);

        if ($follow->getFollowers()->contains($this)) {
            $follow->removeFollower($this);
        }
    }

    /**
     * Get follows.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollows()
    {
        return $this->follows;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set group
     *
     * @param \App\UserBundle\Entity\Group $group
     *
     * @return User
     */
    public function setGroup(\App\UserBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \App\UserBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Hook on pre-persist operations
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    /**
     * Hook on pre-update operations
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime;
    }

    /**
     * Returns the gender list
     *
     * @return array
     */
    public static function getGenderList()
    {
        return array(
            UserInterface::GENDER_UNKNOWN => 'gender_unknown',
            UserInterface::GENDER_FEMALE  => 'gender_female',
            UserInterface::GENDER_MALE    => 'gender_male',
        );
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zipcode
     *
     * @param integer $zipcode
     *
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return integer
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
}
