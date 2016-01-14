<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TagAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'tags';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'label' => 'Nom',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array(
                'label' => 'id',
            ))
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('sports', null, array(
                'label' => 'Sports',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, [
                'label' => '#',
            ])
            ->add('name', null, [
                'label' => 'Nom',
            ])
            ->add('_action', 'actions', [
                'actions' => array(
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }
}
