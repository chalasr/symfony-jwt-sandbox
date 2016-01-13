<?php

namespace App\SportBundle\Entity;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tags_sport")
 */
class Tag extends AbstractEntity implements EntityInterface
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Sport", mappedBy="tags")
     */
    protected $sports;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->sports = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName() ?: 'New Tag';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $excludes = null)
    {
        $tag = array(
            'id'     => $this->getId(),
            'name'   => $this->getName(),
            'sports' => array(),
        );

        foreach ($this->getSports() as $sport) {
            $tag['sports'][] = array(
                'id'   => $sport->getId(),
                'name' => $sport->getName(),
            );
        }

        foreach ($excludes as $value) {
            unset($tag[$value]);
        }

        return $tag;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Tag
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
     * Add sport.
     *
     * @param \App\SportBundle\Entity\Sport $sport
     *
     * @return Tag
     */
    public function addSport(\App\SportBundle\Entity\Sport $sport)
    {
        $this->sports[] = $sport;

        return $this;
    }

    /**
     * Remove sport.
     *
     * @param \App\SportBundle\Entity\Sport $sport
     */
    public function removeSport(\App\SportBundle\Entity\Sport $sport)
    {
        $this->sports->removeElement($sport);
    }

    /**
     * Get sports.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSports()
    {
        return $this->sports;
    }
}
