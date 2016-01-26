<?php

namespace App\Util\Controller;

/**
 * Add methods for read users permissions.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
trait CanCheckPermissionsTrait
{
    /**
     * Get security authorization_checker.
     *
     * @return object
     */
    protected function getRolesManager()
    {
        return $this->container->get('security.authorization_checker');
    }

    /**
     * Get current authenticated user.
     *
     * @return User|null
     */
    protected function getCurrentUser()
    {
        return $this->container->get('security.context')->getToken()->getUser();
    }

    /**
     * Check if user has ROLE_ADMIN.
     *
     * @return bool
     */
    protected function isAdmin()
    {
        $rolesManager = $this->getRolesManager();

        return $rolesManager->isGranted('ROLE_ADMIN') || $rolesManager->isGranted('ROLE_SUPER_ADMIN');
    }

    /**
     * Check if user has ROLE_GUEST.
     *
     * @return bool
     */
    protected function isGuest()
    {
        $rolesManager = $this->getRolesManager();

        return $rolesManager->isGranted('ROLE_GUEST') && !$rolesManager->isGranted('ROLE_ADMIN');
    }
}
