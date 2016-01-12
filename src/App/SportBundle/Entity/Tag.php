<?php
// src/App/SportBundle/Entity/Tag.php
namespace App\SportBundle\Entity;;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ @ORM\ManyToOne(targetEntity="Sport", inversedBy="tags")
     * @ @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     */
    private $sport;


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
     * Set name
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sport
     *
     * @param \App\SportBundle\Entity\Sport $sport
     *
     * @return Tag
     */
    public function setSport(\App\SportBundle\Entity\Sport $sport = null)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \App\SportBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }
}
