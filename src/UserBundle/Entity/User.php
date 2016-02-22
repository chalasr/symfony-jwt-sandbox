<?php

/**
 * This file is part of RCH/JWTUserBundle.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
 */
namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RCH\JWTUserBundle\Entity\User as BaseUser;
use JMS\Serializer\Annotation as JMS;

/**
 * User.
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User extends BaseUser
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
     * @var string
     *
     * @JMS\Expose
     */
    protected $email;

    public function __construct()
    {
        parent::__construct();
    }
}
