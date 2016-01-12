<?php

namespace App\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

/**
 * Abstract Admin Controller.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
abstract class AbstractAdminController extends Controller
{
    /**
     * Shortcut method to locate a resource.
     *
     * @param string $resource
     *
     * @return string Resource path
     */
    protected function locate($resource)
    {
        return $this->get('kernel')->locateResource($resource);
    }
}
