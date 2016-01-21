<?php

namespace App\AdminBundle\Admin\User\Information;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;

class ProviderInformationAdmin extends Admin
{
    public function configureFormFields(FormMapper $formMapper){
        $formMapper
            ->add('name', null, array(
                'label' => 'Provider name',
            ));
    }

}
