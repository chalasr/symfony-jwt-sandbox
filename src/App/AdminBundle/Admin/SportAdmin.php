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
    protected $baseRoutePattern = 'sports';

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('show_icon', 'icon/{name}', [], [], ['expose' => true]);
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
                $path = $this->generateUrl('show_icon', ['name' => $subject->getIcon()]);
                $iconFieldOptions['help'] = sprintf('<img style="max-width: 100px;" src="%s"/>', $path);
            }
        }

        $formMapper
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('categories', null, array(
                'label' => 'Categories',
            ))
            ->add('isActive', null, array(
                'label' => 'Actif',
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
