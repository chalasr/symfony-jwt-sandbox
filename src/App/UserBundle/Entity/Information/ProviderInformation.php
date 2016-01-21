<?php

namespace App\UserBundle\Entity\Information;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Province.
 *
 * @ORM\Entity
 * @ORM\Table(name="provider_user")
 *
 * @JMS\ExclusionPolicy("all")
 */
class ProviderInformation extends AbstractEntity implements EntityInterface
{
    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="coachInformation")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     * @JMS\Expose
     */
    protected $name;

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: '';
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Provider
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user.
     *
     * @param \App\UserBundle\Entity\User $user
     *
     * @return ProviderInformation
     */
    public function setUser(\App\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \App\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
