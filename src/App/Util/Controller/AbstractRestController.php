<?php

namespace App\Util\Controller;

use App\Util\Controller\EntitySerializableTrait as EntitySerializable;
use App\Util\Controller\LocalizableTrait as Localizable;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractRestController extends Controller
{
    use Localizable, EntitySerializable;

    /**
     * Get current authenticated user.
     *
     * @return User|null
     */
    protected function getCurrentUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }

    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

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
     * Check if user has ROLE_ADMIN.
     *
     * @return bool
     */
    protected function isAdmin()
    {
        return $this->getRolesManager()->isGranted('ROLE_ADMIN');
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
     * Get view handler.
     *
     * @return ViewHandler
     */
    protected function getViewHandler()
    {
        return $this->get('fos_rest.view_handler');
    }

    /**
     * Handle view.
     *
     * @param int        $statusCode
     * @param mixed|null $data
     *
     * @return View
     */
    protected function handleView($statusCode = 200, $data = null)
    {
        $view = View::create()->setStatusCode($statusCode);

        if ($data) {
            $view->setData($data);
        }

        return $this->getViewHandler()->handle($view);
    }
}
