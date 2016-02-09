<?php

namespace App\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="events")
 * @ORM\Entity
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @ORM\Column(name="location_address", type="text")
     */
    private $locationAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="location_long", type="string", length=500)
     */
    private $locationLong;

    /**
     * @var string
     *
     * @ORM\Column(name="location_lat", type="string", length=500)
     */
    private $locationLat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="canceled", type="boolean")
     */
    private $canceled = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private = false;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;

    /** @ORM\OneToOne(targetEntity="\App\EventBundle\Entity\EventType\Single", mappedBy="event", cascade={"persist"}) */
    private $singleEvent;

    /** @ORM\OneToOne(targetEntity="\App\EventBundle\Entity\EventType\Open", mappedBy="event", cascade={"persist"}) */
    private $openEvent;

    /** @ORM\OneToOne(targetEntity="\App\EventBundle\Entity\EventType\Cyclic", mappedBy="event", cascade={"persist"}) */
    private $cyclicEvent;

    /** @ORM\OneToOne(targetEntity="\App\UserBundle\Entity\User", mappedBy="event", cascade={"persist"}) */
    private $user;

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
     * Set title
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set picture
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
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set description
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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set locationAddress
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
     * Get locationAddress
     *
     * @return string
     */
    public function getLocationAddress()
    {
        return $this->locationAddress;
    }

    /**
     * Set locationLong
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
     * Get locationLong
     *
     * @return string
     */
    public function getLocationLong()
    {
        return $this->locationLong;
    }

    /**
     * Set locationLat
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
     * Get locationLat
     *
     * @return string
     */
    public function getLocationLat()
    {
        return $this->locationLat;
    }

    /**
     * Set canceled
     *
     * @param boolean $canceled
     *
     * @return Event
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;

        return $this;
    }

    /**
     * Get canceled
     *
     * @return boolean
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set private
     *
     * @param boolean $private
     *
     * @return Event
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set price
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
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set singleEvent
     *
     * @param \App\EventBundle\Entity\EventType\Single $singleEvent
     *
     * @return Event
     */
    public function setSingleEvent(\App\EventBundle\Entity\EventType\Single $singleEvent = null)
    {
        $this->singleEvent = $singleEvent;

        return $this;
    }

    /**
     * Get singleEvent
     *
     * @return \App\EventBundle\Entity\EventType\Single
     */
    public function getSingleEvent()
    {
        return $this->singleEvent;
    }

    /**
     * Set openEvent
     *
     * @param \App\EventBundle\Entity\EventType\Open $openEvent
     *
     * @return Event
     */
    public function setOpenEvent(\App\EventBundle\Entity\EventType\Open $openEvent = null)
    {
        $this->openEvent = $openEvent;

        return $this;
    }

    /**
     * Get openEvent
     *
     * @return \App\EventBundle\Entity\EventType\Open
     */
    public function getOpenEvent()
    {
        return $this->openEvent;
    }

    /**
     * Set cyclicEvent
     *
     * @param \App\EventBundle\Entity\EventType\Cyclic $cyclicEvent
     *
     * @return Event
     */
    public function setCyclicEvent(\App\EventBundle\Entity\EventType\Cyclic $cyclicEvent = null)
    {
        $this->cyclicEvent = $cyclicEvent;

        return $this;
    }

    /**
     * Get cyclicEvent
     *
     * @return \App\EventBundle\Entity\EventType\Cyclic
     */
    public function getCyclicEvent()
    {
        return $this->cyclicEvent;
    }
}
