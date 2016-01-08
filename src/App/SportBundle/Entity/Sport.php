<?php

namespace App\SportBundle\Entity;

use App\Util\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sport.
 *
 * @ORM\Table(name="sports")
 * @ORM\Entity
 */
class Sport implements EntityInterface
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
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="sports", cascade={"all"})
     * @ORM\JoinTable(name="sports_categories")
     */
    protected $categories;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName() ?: 'Nouveau Sport';
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        $sport = array(
            'id'         => $this->getId(),
            'name'       => $this->getName(),
            'isActive'   => $this->getIsActive(),
            'categories' => array(),
        );

        foreach ($this->getCategories() as $cat) {
            $sport['categories'][] = array(
                'id'   => $cat->getId(),
                'name' => $cat->getName(),
            );
        }

        return $sport;
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
     * @return Sport
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
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return Sport
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add category.
     *
     * @param \App\SportBundle\Entity\Category $category
     *
     * @return Sport
     */
    public function addCategory(\App\SportBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category.
     *
     * @param \App\SportBundle\Entity\Category $category
     */
    public function removeCategory(\App\SportBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
