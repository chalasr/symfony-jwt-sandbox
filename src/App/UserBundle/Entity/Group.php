<?php

namespace App\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseGroup;

/**
 * Group.
 *
 * @ORM\Table(name="fos_user_group")
 * @ORM\Entity
 */
class Group extends BaseGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

}
