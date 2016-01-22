<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;

class SportrooperAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_sportrooper';

    protected $baseRoutePattern = 'users/sportroopers';

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
