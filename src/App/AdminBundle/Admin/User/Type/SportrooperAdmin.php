<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;

class SportrooperAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_sportrooper';

    protected $baseRoutePattern = 'users/sportroopers';
}
