<?php

namespace App\EventBundle\Entity\Type;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Single.
 *
 * @ORM\Table(name="single_events")
 * @ORM\Entity
 */
class Single extends AbstractEntity implements EntityInterface
{
    /**
     * @var bool
     *
     * @ORM\Column(name="for_kids", type="boolean")
     */
    private $forKids = false;

    /**
     * @var int
     *
     * @ORM\Column(name="kids_min_age", type="integer", nullable=true)
     */
    private $kidsMinAge;

    /**
     * @var int
     *
     * @ORM\Column(name="kids_max_age", type="integer", nullable=true)
     */
    private $kidsMaxAge;

    /**
     * @var int
     *
     * @ORM\Column(name="min_participants", type="integer", nullable=true)
     */
    private $minParticipants;

    /**
     * @var int
     *
     * @ORM\Column(name="max_participants", type="integer", nullable=true)
     */
    private $maxParticipants;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="auto_cancelable", type="boolean")
     */
    private $autoCancelable = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cancelation_time", type="datetime", nullable=true)
     */
    private $cancelationTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time")
     */
    private $time;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     */
    private $duration;

    /**
     * @var bool
     *
     * @ORM\Column(name="cyclic", type="boolean")
     */
    private $cyclic;

    /**
     * @var bool
     *
     * @ORM\Column(name="coached", type="boolean")
     */
    private $coached;

    /** @ORM\OneToOne(targetEntity="App\EventBundle\Entity\Event", cascade={"persist", "remove"}) */
    protected $event;

    public function __toString()
    {
        return $this->event ? $this->event->getTitle() : 'New Single Event';
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
     * Set forKids.
     *
     * @param bool $forKids
     *
     * @return Single
     */
    public function setForKids($forKids)
    {
        $this->forKids = $forKids;

        return $this;
    }

    /**
     * Get forKids.
     *
     * @return bool
     */
    public function getForKids()
    {
        return $this->forKids;
    }

    /**
     * Set kidsMinAge.
     *
     * @param int $kidsMinAge
     *
     * @return Single
     */
    public function setKidsMinAge($kidsMinAge)
    {
        $this->kidsMinAge = $kidsMinAge;

        return $this;
    }

    /**
     * Get kidsMinAge.
     *
     * @return int
     */
    public function getKidsMinAge()
    {
        return $this->kidsMinAge;
    }

    /**
     * Set kidsMaxAge.
     *
     * @param int $kidsMaxAge
     *
     * @return Single
     */
    public function setKidsMaxAge($kidsMaxAge)
    {
        $this->kidsMaxAge = $kidsMaxAge;

        return $this;
    }

    /**
     * Get kidsMaxAge.
     *
     * @return int
     */
    public function getKidsMaxAge()
    {
        return $this->kidsMaxAge;
    }

    /**
     * Set minParticipants.
     *
     * @param int $minParticipants
     *
     * @return Single
     */
    public function setMinParticipants($minParticipants)
    {
        $this->minParticipants = $minParticipants;

        return $this;
    }

    /**
     * Get minParticipants.
     *
     * @return int
     */
    public function getMinParticipants()
    {
        return $this->minParticipants;
    }

    /**
     * Set maxParticipants.
     *
     * @param int $maxParticipants
     *
     * @return Single
     */
    public function setMaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * Get maxParticipants.
     *
     * @return int
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * Set date.
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
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set autoCancelable.
     *
     * @param bool $autoCancelable
     *
     * @return Single
     */
    public function setAutoCancelable($autoCancelable)
    {
        $this->autoCancelable = $autoCancelable;

        return $this;
    }

    /**
     * Get autoCancelable.
     *
     * @return bool
     */
    public function getAutoCancelable()
    {
        return $this->autoCancelable;
    }

    /**
     * Set cancelationTime.
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
     * Get cancelationTime.
     *
     * @return \DateTime
     */
    public function getCancelationTime()
    {
        return $this->cancelationTime;
    }

    /**
     * Set time.
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
     * Get time.
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set duration.
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
     * Get duration.
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set cyclic.
     *
     * @param bool $cyclic
     *
     * @return Single
     */
    public function setCyclic($cyclic)
    {
        $this->cyclic = $cyclic;

        return $this;
    }

    /**
     * Get cyclic.
     *
     * @return bool
     */
    public function getCyclic()
    {
        return $this->cyclic;
    }

    /**
     * Set coached.
     *
     * @param bool $coached
     *
     * @return Single
     */
    public function setCoached($coached)
    {
        $this->coached = $coached;

        return $this;
    }

    /**
     * Get coached.
     *
     * @return bool
     */
    public function getCoached()
    {
        return $this->coached;
    }

    /**
     * Set event.
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
     * Get event.
     *
     * @return \App\EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
