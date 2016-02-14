<?php

namespace App\EventBundle\Entity\Type;

use Doctrine\ORM\Mapping as ORM;

/**
 * Open.
 *
 * @ORM\Table(name="open_events")
 * @ORM\Entity
 */
class Open
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
     * @ORM\Column(name="due_date", type="datetime")
     */
    private $dueDate;

    /** @ORM\OneToOne(targetEntity="App\EventBundle\Entity\Event", cascade={"persist", "remove"}) */
    protected $event;

    /**
     * To string.
     *
     * @return string
     */
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
     * Set dueDate.
     *
     * @param \DateTime $dueDate
     *
     * @return Open
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate.
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set event.
     *
     * @param \App\EventBundle\Entity\Event $event
     *
     * @return Open
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
