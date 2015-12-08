<?php

/**
 * This file is part of the Sportroops project.
 *
 * (c) <Robin Chalas> <rchalas@sutunam.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * User entity.
 *
 * @author <Robin Chalas> <rchalas@sutunam.com>
 */
class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
}
