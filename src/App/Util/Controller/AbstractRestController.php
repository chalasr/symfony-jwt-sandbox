<?php

namespace App\Util\Controller;

use App\Util\Controller\CanSerializeTrait as CanSerialize;
use App\Util\Controller\LocalizableTrait as Localizable;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Abstract class for Rest Controllers.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
abstract class AbstractRestController extends Controller
{
    use Localizable, CanSerialize;

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

    /**
     * Returns authentication provider.
     *
     * @return UserManager $userManager
     */
    protected function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }
}
