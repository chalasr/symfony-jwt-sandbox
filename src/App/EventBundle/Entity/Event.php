<?php

namespace App\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;

/**
 * Event.
 *
 * @ORM\Table(name="events")
 * @ORM\Entity
 */
class Event extends AbstractEntity implements EntityInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=500)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location_address", type="text", nullable=true)
     */
    private $locationAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="location_long", type="string", length=500, nullable=true)
     */
    private $locationLong;

    /**
     * @var string
     *
     * @ORM\Column(name="location_lat", type="string", length=500, nullable=true)
     */
    private $locationLat;

    /**
     * @var bool
     *
     * @ORM\Column(name="canceled", type="boolean")
     */
    private $canceled = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private = false;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price = 0;

    /** @ORM\OneToOne(targetEntity="\App\EventBundle\Entity\Type\Single", mappedBy="event", cascade={"persist"}) */
    private $singleEvent;

    /** @ORM\OneToOne(targetEntity="\App\EventBundle\Entity\Type\Open", mappedBy="event", cascade={"persist"}) */
    private $openEvent;

    /** @ORM\OneToOne(targetEntity="\App\UserBundle\Entity\User", cascade={"persist"}) */
    private $user;

    /** @ORM\OneToOne(targetEntity="\App\SportBundle\Entity\Sport", cascade={"persist"}) */
    private $sport;

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->title ?: 'Nouvel évènement';
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set picture.
     *
     * @param string $picture
     *
     * @return Event
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture.
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set locationAddress.
     *
     * @param string $locationAddress
     *
     * @return Event
     */
    public function setLocationAddress($locationAddress)
    {
        $this->locationAddress = $locationAddress;

        return $this;
    }

    /**
     * Get locationAddress.
     *
     * @return string
     */
    public function getLocationAddress()
    {
        return $this->locationAddress;
    }

    /**
     * Set locationLong.
     *
     * @param string $locationLong
     *
     * @return Event
     */
    public function setLocationLong($locationLong)
    {
        $this->locationLong = $locationLong;

        return $this;
    }

    /**
     * Get locationLong.
     *
     * @return string
     */
    public function getLocationLong()
    {
        return $this->locationLong;
    }

    /**
     * Set locationLat.
     *
     * @param string $locationLat
     *
     * @return Event
     */
    public function setLocationLat($locationLat)
    {
        $this->locationLat = $locationLat;

        return $this;
    }

    /**
     * Get locationLat.
     *
     * @return string
     */
    public function getLocationLat()
    {
        return $this->locationLat;
    }

    /**
     * Set canceled.
     *
     * @param bool $canceled
     *
     * @return Event
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;

        return $this;
    }

    /**
     * Get canceled.
     *
     * @return bool
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set private.
     *
     * @param bool $private
     *
     * @return Event
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private.
     *
     * @return bool
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return Event
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
     * Set singleEvent.
     *
     * @param \App\EventBundle\Entity\Type\Single $singleEvent
     *
     * @return Event
     */
    public function setSingleEvent(\App\EventBundle\Entity\Type\Single $singleEvent = null)
    {
        $this->singleEvent = $singleEvent;

        return $this;
    }

    /**
     * Get singleEvent.
     *
     * @return \App\EventBundle\Entity\Type\Single
     */
    public function getSingleEvent()
    {
        return $this->singleEvent;
    }

    /**
     * Set openEvent.
     *
     * @param \App\EventBundle\Entity\Type\Open $openEvent
     *
     * @return Event
     */
    public function setOpenEvent(\App\EventBundle\Entity\Type\Open $openEvent = null)
    {
        $this->openEvent = $openEvent;

        return $this;
    }

    /**
     * Get openEvent.
     *
     * @return \App\EventBundle\Entity\Type\Open
     */
    public function getOpenEvent()
    {
        return $this->openEvent;
    }

    /**
     * Set cyclicEvent.
     *
     * @param \App\EventBundle\Entity\Type\Cyclic $cyclicEvent
     *
     * @return Event
     */
    public function setCyclicEvent(\App\EventBundle\Entity\Type\Cyclic $cyclicEvent = null)
    {
        $this->cyclicEvent = $cyclicEvent;

        return $this;
    }

    /**
     * Get cyclicEvent.
     *
     * @return \App\EventBundle\Entity\Type\Cyclic
     */
    public function getCyclicEvent()
    {
        return $this->cyclicEvent;
    }

    /**
     * Set user.
     *
     * @param \App\UserBundle\Entity\User $user
     *
     * @return Event
     */
    public function setUser(\App\UserBundle\Entity\User $user = null)
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

    /**
     * Set sport.
     *
     * @param \App\SportBundle\Entity\Sport $sport
     *
     * @return Event
     */
    public function setSport(\App\SportBundle\Entity\Sport $sport = null)
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
}
