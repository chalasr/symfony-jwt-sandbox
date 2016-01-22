<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

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
        parent::configureListFields($listMapper);
        $listMapper
            ->add('_action', 'actions', [
                'actions' => array(
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }
}
