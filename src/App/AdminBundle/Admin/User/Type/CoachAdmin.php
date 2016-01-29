<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CoachAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_coach';

    protected $baseRoutePattern = 'users/coachs';

    public function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('coachInformation', 'sonata_type_admin', array(
                'by_reference' => false,
                'required'     => false,
                'label'        => false,
            ), array(
                'edit'       => 'inline',
                'admin_code' => 'sonata.admin.coach_information',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'Id'))
            ->addIdentifier('email')
            ->add('firstname', null, array('label' => 'Prénom'))
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('phone', null, array('label' => 'Téléphone'))
            ->add('createdAt', 'date', array('label' => 'Créé le', 'format' => 'd/m/Y'))
            ->add('_action', 'actions', [
                'actions' => array(
                    'show'   => [],
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('email')
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('group')
        ;
    }
}
