<?php

namespace App\AdminBundle\Admin\User\Information;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class CoachInformationAdmin extends Admin
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('proCardExpirationDate', 'sonata_type_date_picker', array(
                'label'       => 'Date d\'expiration de carte pro',
                'format'      => 'dd/MM/yyyy',
                'dp_language' => 'fr',
            ))
            ->add('insuranceCompanyName', null, array(
                'label' => 'Nom de la compagnie fournissant la police d\'assurance',
            ))
            ->add('insurancePolicyNumber', null, array(
                'label' => 'Numéro de police d\'insurance',
            ))
            ->add('insurancePolicyExpirationDate', 'sonata_type_date_picker', array(
                'label'       => 'Date d\'expiration de la police d\'assurance',
                'format'      => 'dd/MM/yyyy',
                'dp_language' => 'fr',
            ))
            ->end()
        ;
        // SNIP;
    }
}
