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
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var array
     *
     * @ORM\Column(name="days", type="array")
     */
    private $days;

    /**
     * @var array
     */
    private $virtualDays;

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

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->singleEvent ? $this->singleEvent->getEvent()->getTitle() : 'New Single Event';
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
     * Set days
     *
     * @param array $days
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
     * @return array
     */
    public function getDays()
    {
        return $this->days;
    }

    public function addVirtualDay($virtualDay)
    {
        $this->virtualDays[$virtualDay] = $virtualDay;
        $this->days = $this->virtualDays;

        return $this;
    }

    public function removeVirtualDay($virtualDay)
    {
        unset($this->virtualDays[$virtualDay]);
        $this->days = $this->virtualDays;

        return $this;
    }

    /**
     * Get virtual days.
     *
     * @return
     */
    public function getVirtualDays()
    {
        if ($this->virtualDays) {
            return $this->virtualDays;
        }

        $this->virtualDays = array();

        if (!$this->days) {
            return $this->virtualDays;
        }

        foreach ($this->days as $day) {
            $this->virtualDays[$day] = $day;
        }

        return $this->virtualDays;
    }

    /**
     * Set singleEvent
     *
     * @param \App\EventBundle\Entity\Type\Single $singleEvent
     *
     * @return Cyclic
     */
    public function setSingleEvent(\App\EventBundle\Entity\Type\Single $singleEvent = null)
    {
        $this->singleEvent = $singleEvent;

        return $this;
    }

    /**
     * Get singleEvent
     *
     * @return \App\EventBundle\Entity\Type\Single
     */
    public function getSingleEvent()
    {
        return $this->singleEvent;
    }
}
