<?php

namespace App\AdminBundle\Admin\User\Information;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class CoachDocumentAdmin extends AbstractAdmin
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // ->add('type', null, array(
            //     'label' => 'File type',
            // ))
            ->add('urlFile', 'file', array(
                'label'      => 'Upload',
                'data_class' => null,
            ));
        // SNIP;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($document)
    {
        $uploadPath = $this->locateResource('@AppUserBundle/Resources/public/coach_documents');

        if ($document->getUrlFile()) {
            $document->uploadDocument($uploadPath);
        }
    }

    public function prePersist($document)
    {
        $uploadPath = $this->locateResource('@AppUserBundle/Resources/public/coach_documents');

        if ($document->getUrlFile()) {
            $document->uploadDocument($uploadPath);
        }

        return $document;
    }
}
