<?php

namespace App\SportBundle\Entity;

use App\Util\Entity\AbstractEntity;
use App\Util\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Util\Entity\AbstractRepository")
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
     * To array.
     *
     * @param array $exclude Excluded parameters
     *
     * @return array
     */
    public function toArray($excludes = null)
    {
        $tags = array(
            'id'     => $this->getId(),
            'name'   => $this->getName(),
            'sports' => array(),
        );

        foreach ($this->getSports() as $sport) {
            $tags['sports'][] = array(
                'id'   => $sport->getId(),
                'name' => $sport->getName(),
            );
        }

        foreach ($excludes as $value) {
            unset($tags[$value]);
        }

        return $tags;
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
