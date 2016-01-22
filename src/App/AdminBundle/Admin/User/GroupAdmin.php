<?php

namespace App\AdminBundle\Admin\User;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GroupAdmin extends AbstractAdmin
{
    protected $formOptions = array(
        'validation_groups' => 'Registration',
    );

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $class = $this->getClass();

        return new $class('', array());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('roles')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');
        $rolesChoices = self::flattenRoles($roles);
        $formMapper
            ->add('name')
            ->add('roles', 'choice', array(
               'choices'  => $rolesChoices,
               'multiple' => true,
            ))
            // ->add('roles', 'security.role_hierarchy.roles', array(
            //     'expanded' => true,
            //     'multiple' => true,
            //     'required' => false
            // ))
        ;
    }

    /**
     * Turns the role's array keys into strings.
     */
    protected static function flattenRoles($rolesHierarchy)
    {
        $flatRoles = array();
        foreach ($rolesHierarchy as $roles) {
            if (empty($roles)) {
                continue;
            }

            foreach ($roles as $role) {
                if (!isset($flatRoles[$role])) {
                    $flatRoles[$role] = $role;
                }
            }
        }

        return $flatRoles;
    }
}
