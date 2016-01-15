<?php

namespace App\Util\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abstract Entity.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
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

    /**
     * Returns self as array.
     *
     * @return array
     */
    public function asArray(array $excludes = null)
    {
        $arrayResult = array();
        $self = new \ReflectionClass($this);

        foreach ($self->getProperties() as $property) {
            $getter = 'get'.ucfirst($property->name);
            $arrayResult[$property] = $entity->$getter();
        }

        return $arrayResult;
    }
}
