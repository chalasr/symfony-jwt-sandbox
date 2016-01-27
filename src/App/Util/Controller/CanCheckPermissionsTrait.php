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
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (null === $user) {
            throw new NotFoundHttpException('Aucun utilisateur connecté');
        }

        return $user;
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

    /**
     * Check if user is the current user.
     *
     * @param User $user
     *
     * @return bool
     */
    protected function isCurrentUser($user)
    {
        return $user->getId() == $this->getCurrentUser()->getId();
    }
}
