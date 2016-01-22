<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;

class AdminsAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_admins';

    protected $baseRoutePattern = 'users/admins';
}
