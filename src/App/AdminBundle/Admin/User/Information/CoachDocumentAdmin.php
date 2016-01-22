<?php

namespace App\AdminBundle\Admin\User\Information;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class CoachDocumentAdmin extends Admin
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', null, array(
                'label' => 'File type',
            ))
            ->add('urlFile', 'file', array(
                'label' => 'Upload',
            ));
        // SNIP;
    }
}
