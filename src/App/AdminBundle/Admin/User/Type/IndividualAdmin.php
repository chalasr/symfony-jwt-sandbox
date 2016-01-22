<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;

class IndividualAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_individual';

    protected $baseRoutePattern = 'users/individuals';
}
