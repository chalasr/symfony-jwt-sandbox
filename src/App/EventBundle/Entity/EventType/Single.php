<?php

namespace App\EventBundle\Entity\EventType;

use Doctrine\ORM\Mapping as ORM;

/**
 * Single
 *
 * @ORM\Table(name="single_events")
 * @ORM\Entity
 */
class Single
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
     * @var boolean
     *
     * @ORM\Column(name="for_kids", type="boolean")
     */
    private $forKids;

    /**
     * @var integer
     *
     * @ORM\Column(name="kids_min_age", type="integer")
     */
    private $kidsMinAge;

    /**
     * @var integer
     *
     * @ORM\Column(name="kids_max_age", type="integer")
     */
    private $kidsMaxAge;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_participants", type="integer")
     */
    private $minParticipants;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_participants", type="integer")
     */
    private $maxParticipants;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="auto_cancelable", type="boolean")
     */
    private $autoCancelable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cancelation_time", type="datetime")
     */
    private $cancelationTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     */
    private $duration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cyclic", type="boolean")
     */
    private $cyclic;

    /**
     * @var boolean
     *
     * @ORM\Column(name="coached", type="boolean")
     */
    private $coached;

    /** @ORM\OneToOne(targetEntity="App\EventBundle\Entity\Event", cascade={"persist", "remove"}) */
    protected $event;

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
     * Set forKids
     *
     * @param boolean $forKids
     *
     * @return Single
     */
    public function setForKids($forKids)
    {
        $this->forKids = $forKids;

        return $this;
    }

    /**
     * Get forKids
     *
     * @return boolean
     */
    public function getForKids()
    {
        return $this->forKids;
    }

    /**
     * Set kidsMinAge
     *
     * @param integer $kidsMinAge
     *
     * @return Single
     */
    public function setKidsMinAge($kidsMinAge)
    {
        $this->kidsMinAge = $kidsMinAge;

        return $this;
    }

    /**
     * Get kidsMinAge
     *
     * @return integer
     */
    public function getKidsMinAge()
    {
        return $this->kidsMinAge;
    }

    /**
     * Set kidsMaxAge
     *
     * @param integer $kidsMaxAge
     *
     * @return Single
     */
    public function setKidsMaxAge($kidsMaxAge)
    {
        $this->kidsMaxAge = $kidsMaxAge;

        return $this;
    }

    /**
     * Get kidsMaxAge
     *
     * @return integer
     */
    public function getKidsMaxAge()
    {
        return $this->kidsMaxAge;
    }

    /**
     * Set minParticipants
     *
     * @param integer $minParticipants
     *
     * @return Single
     */
    public function setMinParticipants($minParticipants)
    {
        $this->minParticipants = $minParticipants;

        return $this;
    }

    /**
     * Get minParticipants
     *
     * @return integer
     */
    public function getMinParticipants()
    {
        return $this->minParticipants;
    }

    /**
     * Set maxParticipants
     *
     * @param integer $maxParticipants
     *
     * @return Single
     */
    public function setMaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * Get maxParticipants
     *
     * @return integer
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Single
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set autoCancelable
     *
     * @param boolean $autoCancelable
     *
     * @return Single
     */
    public function setAutoCancelable($autoCancelable)
    {
        $this->autoCancelable = $autoCancelable;

        return $this;
    }

    /**
     * Get autoCancelable
     *
     * @return boolean
     */
    public function getAutoCancelable()
    {
        return $this->autoCancelable;
    }

    /**
     * Set cancelationTime
     *
     * @param \DateTime $cancelationTime
     *
     * @return Single
     */
    public function setCancelationTime($cancelationTime)
    {
        $this->cancelationTime = $cancelationTime;

        return $this;
    }

    /**
     * Get cancelationTime
     *
     * @return \DateTime
     */
    public function getCancelationTime()
    {
        return $this->cancelationTime;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Single
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
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
     * Set duration
     *
     * @param float $duration
     *
     * @return Single
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set cyclic
     *
     * @param boolean $cyclic
     *
     * @return Single
     */
    public function setCyclic($cyclic)
    {
        $this->cyclic = $cyclic;

        return $this;
    }

    /**
     * Get cyclic
     *
     * @return boolean
     */
    public function getCyclic()
    {
        return $this->cyclic;
    }

    /**
     * Set coached
     *
     * @param boolean $coached
     *
     * @return Single
     */
    public function setCoached($coached)
    {
        $this->coached = $coached;

        return $this;
    }

    /**
     * Get coached
     *
     * @return boolean
     */
    public function getCoached()
    {
        return $this->coached;
    }

    /**
     * Set event
     *
     * @param \App\EventBundle\Entity\Event $event
     *
     * @return Single
     */
    public function setEvent(\App\EventBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \App\EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
