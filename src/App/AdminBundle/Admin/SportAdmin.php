<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\Routecollection;

/**
 * Sport admin class.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class SportAdmin extends AbstractAdmin
{
    // Define the base uri for the admin class
    // e.g. /admin/sports
    protected $baseRoutePattern = 'sports';

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        // Add a custom route from a method created in the corresponding AdminController
        // Here, I set a route using 'show_icon'.
        // It's an alias for retrieve the showIconAction method in the SportAdminController
        $collection->add('show_icon', 'icon/{sport}', [], [], ['expose' => true]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $iconFieldOptions =  array(
            'required'   => false,
            'data_class' => null,
            'label'      => 'Icône',
        );

        if ($this->getSubject()->getId()) {
            $subject = $this->getSubject();
            if ($subject->getIcon()) {
                $path = $this->generateUrl('show_icon', ['sport' => $subject->getName()]);
                $iconFieldOptions['help'] = sprintf('<div class="icon_prev"><img src="%s"/></div>', $path);
            }
        }

        $formMapper
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('categories', null, array(
                'label' => 'Categories',
            ))
            ->add('tags', null, array(
                'label' => 'Tags',
            ))
            ->add('isActive', null, array(
                'label'    => 'Actif',
                'required' => false,
            ))
            ->add('file', 'file', $iconFieldOptions)
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
            ->add('categories', null, array(
                'label' => 'Categories',
            ))
            ->add('isActive', null, array(
                'label' => 'Actif',
            ));
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
            ->add('categories', null, [
                'label' => 'Categories',
            ])
            ->add('tags', null, [
                'label' => 'Categories',
            ])
            ->add('isActive', null, [
                'label' => 'Actif',
            ])
            ->add('_action', 'actions', [
                'actions' => array(
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($created)
    {
        $uploadPath = $this->locate('@AppSportBundle/Resources/public/icons');
        if ($created->getFile()) {
            $created->uploadIcon($uploadPath);
        }

        return $created;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($updated)
    {
        $uploadPath = $this->locate('@AppSportBundle/Resources/public/icons');

        if ($updated->getFile()) {
            $updated->uploadIcon($uploadPath);
        }

        return $updated;
    }
}
