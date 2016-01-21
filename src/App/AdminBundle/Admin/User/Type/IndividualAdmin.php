<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;
use Sonata\UserBundle\Model\UserInterface;

class IndividualAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_individual';

    protected $baseRoutePattern = 'users/individuals';
}
