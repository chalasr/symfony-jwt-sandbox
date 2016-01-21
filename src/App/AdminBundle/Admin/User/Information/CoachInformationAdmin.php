<?php

namespace App\AdminBundle\Admin\User\Information;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;

class CoachInformationAdmin extends Admin
{
    public function configureFormFields(FormMapper $formMapper){
        $formMapper
            ->add('proCardExpirationDate', null, array(
                'label' => 'Card expiration date',
            ))
            ->add('insuranceCompanyName', null, array(
                'label' => 'Insurance company name',
            ))
            ->add('insurancePolicyNumber', null, array(
                'label' => 'Insurance policy number',
            ))
            ->add('insurancePolicyExpirationDate', null, array(
                'label' => 'Insurance policy expiration date',
            ));
    }

}
