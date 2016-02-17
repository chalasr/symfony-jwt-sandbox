<?php

namespace App\AdminBundle\Admin\User\Information;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class CoachDocumentAdmin extends AbstractAdmin
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('urlFile', 'file', array(
                'label'      => 'Upload',
                'data_class' => null,
            ))
            ->add('name', null, array('read_only' => true, 'help' => 'lol'))
            ->add('type', null, array('read_only' => true, 'help' => 'MimeType of file'))
        ;
    }
}
