<?php

namespace App\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\AdminSecurityController as BaseSecurityController;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminSecurityController extends BaseSecurityController
{
    /**
     * Overridden login.
     *
     * @return RedirectResponse|resource
     */
    public function loginAction(Request $request = null)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('sonata_user_profile_show');

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
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        // $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider')
        ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
        : null;

        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $refererUri = $request->server->get('HTTP_REFERER');

            return new RedirectResponse(
                $refererUri && $refererUri != $request->getUri()
                ? $refererUri
                : $this->container->get('router')->generate('sonata_admin_dashboard')
            );
        }

        return $this->container->get('templating')->renderResponse(
            'AppAdminBundle:Security:login.html.twig', array(
                // 'last_username' => $lastUsername,
                'error'         => $error,
                'csrf_token'    => $csrfToken,
                'base_template' => $this->container->get('sonata.admin.pool')->getTemplate('layout'),
                'admin_pool'    => $this->container->get('sonata.admin.pool')
            ));
    }
}
