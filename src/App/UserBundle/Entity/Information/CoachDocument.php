<?php

namespace App\UserBundle\Entity\Information;

use App\Util\Doctrine\Entity\AbstractEntity;
use App\Util\Doctrine\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Province.
 *
 * @ORM\Entity
 * @ORM\Table(name="document_coach")
 */
class CoachDocument extends AbstractEntity implements EntityInterface
{
    const SERVER_PATH_TO_IMAGE_FOLDER = '/media/datas/websites/sportroops.localhost/src/App/UserBundle/Resources/public/documents';

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=512, nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="coachDocuments")
     * @ORM\JoinColumn(name="coach_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    protected $file;

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
     * Set type.
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
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set urlFile.
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
     * Get urlFile.
     *
     * @return string
     */
    public function getUrlFile()
    {
        return $this->urlFile;
    }

    /**
     * Set urlFile.
     *
     * @param string $urlFile
     *
     * @return Document
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set user.
     *
     * @param \App\UserBundle\Entity\User $user
     *
     * @return CoachDocument
     */
    public function setUser(\App\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \App\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Upload attachment file.
     */
    public function uploadDocument($path)
    {
        if (null === $file = $this->getUrlFile()) {
            return;
        }

        $file->move($path, $file->getClientOriginalName());

        $this->setUrlFile($file->getClientOriginalName());
        $this->setName($file->getClientOriginalName());

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return CoachDocument
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
