<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CategoryAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'categories';

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
                'label' => 'id'
            ))
            ->add('name', null, array(
                'label' => 'Nom',
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
                    'edit' => [],
                    'delete' => [],
                )
            ])
        ;
    }
}
