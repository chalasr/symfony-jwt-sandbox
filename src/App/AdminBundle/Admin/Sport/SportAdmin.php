<?php

namespace App\AdminBundle\Admin\Sport;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\Routecollection;

/**
 * Sport admin class.
 *
 * This class represents an entity (Sport here) in our Back Office.
 * It must be declared in the Resources/config/services.yml of this bundle (@AppAdminBundle)
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class SportAdmin extends AbstractAdmin
{
    /**
     *  Define the base uri for the admin class
     *  Here will be accessible on /admin/sports.
     */
    protected $baseRoutePattern = 'sports';

    /**
     * Add a custom route to expose methods from the corresponding AdminController.
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        /*
         *  Here, I set a route using 'show_icon'.
         *  It's an alias for retrieve the showIconAction method in the SportAdminController.
         */
        $collection->add('show_icon', 'icon/{sport}', [], [], ['expose' => true]);
    }

    /**
     * Configure your form fields.
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /* Custom check displaying icon if is it */
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
        /* End custom check */

        /*
         * For each field you want add to the form
         * Do a $formMapper->add()
         *
         * @prototype ->add($name, $type, array $option, array $fieldDescriptionOptions)
         *
         * By default, sonata use the most adapted type corresponding to the property type in database schema.
         * Sometimes you have to use a custom field like autocomplete or model_list
         *
         * @see https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/reference/form_field_definition.html
         * @see https://sonata-project.org/bundles/admin/master/doc/reference/form_types.html
         */
        $formMapper
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('categories', null, array(
                'label' => 'Catégories',
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
     * In the LIST view, you have some filters called datagridFilters.
     * Use the $datagridMapper to configure your list filters.
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        /*
         * Use the Same approach than use formMapper
         *
         * @prototype ->add($name, $type, array $filterOptions, $fieldType, $fieldOptions, array $fieldDescriptionOptions)
         *
         * @see https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/reference/filter_field_definition.html
         */
        $datagridMapper
            ->add('id', null, array(
                'label' => 'id',
            ))
            ->add('name', null, array(
                'label' => 'Nom',
            ))
            ->add('categories', null, array(
                'label' => 'Catégories',
            ))
            ->add('tags', null, array(
                'label' => 'Tags',
            ))
            ->add('isActive', null, array(
                'label' => 'Actif',
            ));
    }

    /**
     * Configure the fields for the LIST view.
     * Use the $listMapper same as $formMapper and $datagridMapper.
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        /*
         * Same approach than use formMapper and datagridMapper
         *
         * @prototype ->add($name, $type, array $fieldDescriptionOptions)
         *
         * @see https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/reference/list_field_definition.html
         */
        $listMapper
            ->add('id', null, [
                'label' => '#',
            ])
            ->add('name', null, [
                'label' => 'Nom',
            ])
            ->add('categories', null, [
                'label' => 'Catégories',
            ])
            ->add('tags', null, [
                'label' => 'Tags',
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
     * Custom method called after submit the CREATE form; before data binding.
     *
     * @param object $created The newly created entity
     */
    public function prePersist($created)
    {
        $uploadPath = $this->locateResource('@AppSportBundle/Resources/public/icons');
        if ($created->getFile()) {
            $created->uploadIcon($uploadPath);
        }

        return $created;
    }

    /**
     * Custom method called after submit the EDIT form; before data binding.
     * Handles icon upload.
     *
     * @param object $updated The updated entity
     */
    public function preUpdate($updated)
    {
        $uploadPath = $this->locateResource('@AppSportBundle/Resources/public/icons');

        if ($updated->getFile()) {
            $updated->uploadIcon($uploadPath);
        }

        return $updated;
    }
}
