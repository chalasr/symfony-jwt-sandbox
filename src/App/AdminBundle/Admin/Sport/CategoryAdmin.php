<?php

namespace App\AdminBundle\Admin\Sport;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

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
                'label' => 'id',
            ))
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('sports', null, array(
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
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }
}
