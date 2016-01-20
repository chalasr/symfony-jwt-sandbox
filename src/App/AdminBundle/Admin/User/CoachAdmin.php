<?php

namespace App\AdminBundle\Admin\User;

class CoachAdmin extends UserAdmin
{
    protected $baseRouteName = 'admin_app_user_coach';
    protected $baseRoutePattern = 'users/coach';
}
