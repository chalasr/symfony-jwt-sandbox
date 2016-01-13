<?php

namespace App\Util\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abstract Entity.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 *
 * @ORM\Entity(repositoryClass="App\Util\Doctrine\Repository\AbstractRepository")
 */
abstract class AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
