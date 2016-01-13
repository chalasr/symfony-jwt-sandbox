<?php

namespace App\SportBundle\Entity;

use App\Util\Entity\EntityInterface;
use App\Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Sport.
 *
 * @ORM\Table(name="sports")
 * @ORM\Entity(repositoryClass="App\Util\Entity\AbstractRepository")
 */
class Sport extends AbstractEntity implements EntityInterface
{
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
    protected $isActive = false;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", nullable=true, length=255)
     */
    protected $icon;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="sports", cascade={"persist"})
     * @ORM\JoinTable(name="sports_categories")
     */
    protected $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="sports", cascade={"persist"})
     * @ORM\JoinTable(name="sports_tags")
     */
    protected $tags;

    /**
     * @var string
     */
    private $file;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * To string.
     *
     * @return string
     */
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
            'tags'       => array(),
        );

        foreach ($this->getCategories() as $cat) {
            $sport['categories'][] = array(
                'id'   => $cat->getId(),
                'name' => $cat->getName(),
            );
        }

        //convert tags to array
        foreach ($this->getTags() as $tag) {
            $sport['tags'][] = array(
                'id'   => $tag->getId(),
                'name' => $tag->getName(),
            );
        }

        return $sport;
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

    /**
     * Set icon.
     *
     * @param string $icon
     *
     * @return Sport
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Upload attachment file.
     */
    public function uploadIcon($path)
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->getFile()->move($path, $this->getFile()->getClientOriginalName());
        $this->setIcon($this->getFile()->getClientOriginalName());

        $this->setFile(null);
    }

    /**
     * Add tag.
     *
     * @param \App\SportBundle\Entity\Tag $tag
     *
     * @return Sport
     */
    public function addTag(\App\SportBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag.
     *
     * @param \App\SportBundle\Entity\Tag $tag
     */
    public function removeTag(\App\SportBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
