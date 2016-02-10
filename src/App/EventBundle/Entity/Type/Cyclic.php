<?php

namespace App\EventBundle\Entity\Type;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cyclic.
 *
 * @ORM\Table(name="cyclic_events")
 * @ORM\Entity
 */
class Cyclic
{
    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="days", type="integer")
     */
    private $days;

    /**
     * @var int
     *
     * @ORM\Column(name="recurrence", type="integer")
     */
    private $recurrence;

    /**
     * @var int
     *
     * @ORM\Column(name="repetition", type="integer")
     */
    private $repetition;

    /** @ORM\OneToOne(targetEntity="App\EventBundle\Entity\Event", cascade={"persist", "remove"}) */
    protected $event;

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
     * Set date.
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
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set days.
     *
     * @param int $days
     *
     * @return Cyclic
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days.
     *
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set recurrence.
     *
     * @param int $recurrence
     *
     * @return Cyclic
     */
    public function setRecurrence($recurrence)
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    /**
     * Get recurrence.
     *
     * @return int
     */
    public function getRecurrence()
    {
        return $this->recurrence;
    }

    /**
     * Set repetition.
     *
     * @param int $repetition
     *
     * @return Cyclic
     */
    public function setRepetition($repetition)
    {
        $this->repetition = $repetition;

        return $this;
    }

    /**
     * Get repetition.
     *
     * @return int
     */
    public function getRepetition()
    {
        return $this->repetition;
    }

    /**
     * Set event.
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
     * Get event.
     *
     * @return \App\EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
