<?php

namespace App\SportBundle\Entity;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Sport.
 *
 * @ORM\Entity
 * @ORM\Table(name="sports")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Sport extends AbstractEntity implements EntityInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @JMS\Expose
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
     *
     * @JMS\Expose
     */
    protected $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="sports", cascade={"persist"})
     * @ORM\JoinTable(name="sports_tags")
     */
    protected $tags;

    /** @ORM\OneToMany(targetEntity="SportUser", mappedBy="sport", cascade={"persist"}) */
    protected $sportUsers;

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
        $this->sportUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: 'New Sport';
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
        $this->setPicture($this->getFile()->getClientOriginalName());

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

    /**
     * Add sportUser
     *
     * @param \App\SportBundle\Entity\SportUser $sportUser
     *
     * @return Sport
     */
    public function addSportUser(\App\SportBundle\Entity\SportUser $sportUser)
    {
        $this->sportUsers[] = $sportUser;

        return $this;
    }

    /**
     * Remove sportUser
     *
     * @param \App\SportBundle\Entity\SportUser $sportUser
     */
    public function removeSportUser(\App\SportBundle\Entity\SportUser $sportUser)
    {
        $this->sportUsers->removeElement($sportUser);
    }

    /**
     * Get sportUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSportUsers()
    {
        return $this->sportUsers;
    }
}
