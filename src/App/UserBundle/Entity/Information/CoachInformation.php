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
 * @ORM\Table(name="coach_user")
 *
 * @JMS\ExclusionPolicy("all")
 */
class CoachInformation extends AbstractEntity implements EntityInterface
{
    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="coachInformation")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="pro_card_expiration_date", type="date", nullable=true)
     */
    protected $proCardExpirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="insurance_company_name", type="string", length=255, nullable=true)
     */
    protected $insuranceCompanyName;

    /**
     * @var string
     *
     * @ORM\Column(name="insurance_policy_number", type="string", length=255, nullable=true)
     */
    protected $insurancePolicyNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="insurance_policy_expiration_date", type="date", nullable=true)
     */
    protected $insurancePolicyExpirationDate;


    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="coachInformation")
     */
    protected $documents;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return 'coachInformation';
    }

    /**
     * Set proCardExpirationDate.
     *
     * @param \DateTime $proCardExpirationDate
     *
     * @return CoachInformation
     */
    public function setProCardExpirationDate($proCardExpirationDate)
    {
        $this->proCardExpirationDate = $proCardExpirationDate;

        return $this;
    }

    /**
     * Get proCardExpirationDate.
     *
     * @return \DateTime
     */
    public function getProCardExpirationDate()
    {
        return $this->proCardExpirationDate;
    }

    /**
     * Set insuranceCompanyName.
     *
     * @param string $insuranceCompanyName
     *
     * @return CoachInformation
     */
    public function setInsuranceCompanyName($insuranceCompanyName)
    {
        $this->insuranceCompanyName = $insuranceCompanyName;

        return $this;
    }

    /**
     * Get insuranceCompanyName.
     *
     * @return string
     */
    public function getInsuranceCompanyName()
    {
        return $this->insuranceCompanyName;
    }

    /**
     * Set insurancePolicyNumber.
     *
     * @param string $insurancePolicyNumber
     *
     * @return CoachInformation
     */
    public function setInsurancePolicyNumber($insurancePolicyNumber)
    {
        $this->insurancePolicyNumber = $insurancePolicyNumber;

        return $this;
    }

    /**
     * Get insurancePolicyNumber.
     *
     * @return string
     */
    public function getInsurancePolicyNumber()
    {
        return $this->insurancePolicyNumber;
    }

    /**
     * Set insurancePolicyExpirationDate.
     *
     * @param \DateTime $insurancePolicyExpirationDate
     *
     * @return CoachInformation
     */
    public function setInsurancePolicyExpirationDate($insurancePolicyExpirationDate)
    {
        $this->insurancePolicyExpirationDate = $insurancePolicyExpirationDate;

        return $this;
    }

    /**
     * Get insurancePolicyExpirationDate.
     *
     * @return \DateTime
     */
    public function getInsurancePolicyExpirationDate()
    {
        return $this->insurancePolicyExpirationDate;
    }

    /**
     * Set user.
     *
     * @param \App\UserBundle\Entity\User $user
     *
     * @return CoachInformation
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
     * Add document
     *
     * @param \App\UserBundle\Entity\Information\Document $document
     *
     * @return CoachInformation
     */
    public function addDocument(\App\UserBundle\Entity\Information\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \App\UserBundle\Entity\Information\Document $document
     */
    public function removeDocument(\App\UserBundle\Entity\Information\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
