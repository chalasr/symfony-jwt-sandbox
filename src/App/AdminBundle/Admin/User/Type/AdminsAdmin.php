<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AdminsAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_admins';

    protected $baseRoutePattern = 'users/admins';
}
