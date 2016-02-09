<?php

namespace App\EventBundle\Entity\EventType;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cyclic
 *
 * @ORM\Table(name="cyclic_events")
 * @ORM\Entity
 */
class Cyclic
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="days", type="integer")
     */
    private $days;

    /**
     * @var integer
     *
     * @ORM\Column(name="recurrence", type="integer")
     */
    private $recurrence;

    /**
     * @var integer
     *
     * @ORM\Column(name="repetition", type="integer")
     */
    private $repetition;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Cyclic
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
     * Set days
     *
     * @param integer $days
     *
     * @return Cyclic
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set recurrence
     *
     * @param integer $recurrence
     *
     * @return Cyclic
     */
    public function setRecurrence($recurrence)
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    /**
     * Get recurrence
     *
     * @return integer
     */
    public function getRecurrence()
    {
        return $this->recurrence;
    }

    /**
     * Set repetition
     *
     * @param integer $repetition
     *
     * @return Cyclic
     */
    public function setRepetition($repetition)
    {
        $this->repetition = $repetition;

        return $this;
    }

    /**
     * Get repetition
     *
     * @return integer
     */
    public function getRepetition()
    {
        return $this->repetition;
    }

    /**
     * Set event
     *
     * @param \App\EventBundle\Entity\Event $event
     *
     * @return Cyclic
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
