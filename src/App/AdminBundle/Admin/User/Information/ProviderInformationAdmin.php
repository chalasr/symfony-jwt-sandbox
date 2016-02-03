<?php

namespace App\AdminBundle\Admin\User\Information;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class ProviderInformationAdmin extends Admin
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', null, array(
            'label' => 'Nom',
        ));
    }
}
