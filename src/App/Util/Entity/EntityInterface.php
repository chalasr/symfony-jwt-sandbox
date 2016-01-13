<?php

namespace App\Util\Entity;

/**
 * Entity interface.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
interface EntityInterface
{
    /**
     * To string.
     */
    public function __toString();

    /**
     * To array.
     */
    public function toArray();
}
