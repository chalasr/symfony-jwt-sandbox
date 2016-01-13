<?php

namespace App\Util\Entity;

use Doctrine\ORM\Mapping as ORM;
use EntityManagerInterface


/**
 * Abstract Entity.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 *
 * @ORM\Entity(repositoryClass="App\Util\Entity\AbstractRepository")
 */
class AbstractEntity
{
    public static $_repository;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->getId();
    }
}
