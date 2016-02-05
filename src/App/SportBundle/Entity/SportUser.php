<?php

namespace App\SportBundle\Entity;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * SportUser.
 *
 * @ORM\Entity
 * @ORM\Table(name="sport_user")
 *
 * @JMS\ExclusionPolicy("all")
 */
class SportUser extends AbstractEntity implements EntityInterface
{
    /**
     * @ORM\Column(type="float")
     */
    protected $price = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="sportUsers")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id", nullable=false)
     */
    protected $sport;

    /**
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="sportUsers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    public function __toString()
    {
        return $this->user && $this->sport
        ? sprintf('User #%d - Sport #%d', $this->user->getId(), $this->sport->getId())
        : 'New Sport-User association';
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return SportUser
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set sport.
     *
     * @param \App\SportBundle\Entity\Sport $sport
     *
     * @return SportUser
     */
    public function setSport(\App\SportBundle\Entity\Sport $sport)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport.
     *
     * @return \App\SportBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * Set user.
     *
     * @param \App\UserBundle\Entity\User $user
     *
     * @return SportUser
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
