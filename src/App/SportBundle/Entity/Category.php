<?php

namespace App\SportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category.
 *
 * @ORM\Table(name="categories_sport")
 * @ORM\Entity
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Sport", mappedBy="categories")
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
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: 'Nouvelle Catégorie';
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
        $category = array(
            'id'     => $this->getId(),
            'name'   => $this->getName(),
            'sports' => array(),
        );

        foreach ($this->getSports() as $sport) {
            $category['sports'][] = array(
                'id'   => $sport->getId(),
                'name' => $sport->getName(),
            );
        }

        foreach ($excludes as $value) {
            unset($category[$value]);
        }

        return $category;
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
     * Set name.
     *
     * @param string $name
     *
     * @return Category
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
     * @return Category
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
