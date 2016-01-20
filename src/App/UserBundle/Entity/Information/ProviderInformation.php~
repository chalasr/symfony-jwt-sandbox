<?php

namespace App\UserBundle\Entity\Information;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Null;

/**
 * Province.
 *
 * @ORM\Entity
 * @ORM\Table(name="provider_user")
 *
 * @JMS\ExclusionPolicy("all")
 */
class ProviderInformation extends AbstractEntity implements EntityInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @JMS\Expose
     */
    protected $name;


    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: "";
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Provider
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

}
