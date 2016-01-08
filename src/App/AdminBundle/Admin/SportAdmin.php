<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class SportAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'sports';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('categories', null , array(
                'label' => 'Categories'
            ))
            ->add('isActive', null , array(
                'label' => 'Actif'
            ));
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
            ->add('isActive', null , array(
                'label' => 'Actif'
            ));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array(
                'label' => '#',
            ))
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('categories', null , array(
                'label' => 'Categories'
            ))
            ->add('isActive', null , array(
                'label' => 'Actif'
            ));
    }
}
