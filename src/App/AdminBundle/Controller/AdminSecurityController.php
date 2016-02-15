<?php

namespace App\AdminBundle\Controller;

use App\Util\Controller\CanCheckPermissionsTrait as CanCheckPermissions;
use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\AdminSecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Custom AdminSecurityController extending from SonataUserBundle.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class AdminSecurityController extends BaseSecurityController
{
    use CanCheckPermissions;

    /**
     * Overridden login.
     *
     * @return RedirectResponse|Response
     */
    public function loginAction(Request $request = null)
    {
        $user = $this->getCurrentUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('sonata_admin_dashboard');

            return new RedirectResponse($url);
        }

        $request = $this->container->get('request');
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            $error = $error->getMessage();
        }

        $csrfToken = $this->container->has('form.csrf_provider')
        ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
        : null;

        if (true === $this->isAdmin()) {
            $refererUri = $request->server->get('HTTP_REFERER');

            return new RedirectResponse(
                $refererUri && $refererUri != $request->getUri()
                ? $refererUri
                : $this->container->get('router')->generate('sonata_admin_dashboard')
            );
        }

        return $this->container->get('templating')->renderResponse(
            'AppAdminBundle:Security:login.html.twig', array(
                'error'         => $error,
                'csrf_token'    => $csrfToken,
                'base_template' => $this->container->get('sonata.admin.pool')->getTemplate('layout'),
                'admin_pool'    => $this->container->get('sonata.admin.pool'),
            )
        );
    }

    /**
     * Show current user's profile.
     *
     * @return Response Forwarded Response instance
     */
    public function showProfileAction()
    {
        return $this->forward('SonataAdminBundle:CRUD:show', array(
            'id'            => $this->getCurrentUser()->getId(),
            '_sonata_admin' => 'sonata.user.admin.user',
            '_sonata_name'  => 'admin_app_user_user_show',
        ));
    }

    /**
     * Edit user profile.
     *
     * @return Response Forwarded Response instance
     */
    public function editProfileAction()
    {
        return $this->forward('SonataAdminBundle:CRUD:edit', array(
            'id'            => $this->getCurrentUser()->getId(),
            '_sonata_admin' => 'sonata.user.admin.user',
            '_sonata_name'  => 'admin_app_user_user_edit',
        ));
    }
}
