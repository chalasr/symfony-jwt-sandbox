<?php

namespace App\UserBundle\Entity\Information;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Province.
 *
 * @ORM\Entity
 * @ORM\Table(name="document_coach")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Document extends AbstractEntity implements EntityInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;


    /**
     * @var string
     *
     * @ORM\Column(name="url_file", type="string", length=512, nullable=true)
     */
    protected $urlFile;

    /**
     * @ORM\ManyToOne(targetEntity="CoachInformation", inversedBy="documents")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id");
     */
    protected $coachInformation;


    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Document';
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Document
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set urlFile
     *
     * @param string $urlFile
     *
     * @return Document
     */
    public function setUrlFile($urlFile)
    {
        $this->urlFile = $urlFile;

        return $this;
    }

    /**
     * Get urlFile
     *
     * @return string
     */
    public function getUrlFile()
    {
        return $this->urlFile;
    }

    /**
     * Set coachInformation
     *
     * @param \App\UserBundle\Entity\Information\CoachInformation $coachInformation
     *
     * @return Document
     */
    public function setCoachInformation(\App\UserBundle\Entity\Information\CoachInformation $coachInformation = null)
    {
        $this->coachInformation = $coachInformation;

        return $this;
    }

    /**
     * Get coachInformation
     *
     * @return \App\UserBundle\Entity\Information\CoachInformation
     */
    public function getCoachInformation()
    {
        return $this->coachInformation;
    }
}
