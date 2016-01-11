<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Controller of Sport admin class.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class SportAdminController extends AbstractAdminController
{
    /**
     * Returns icon of a sport in response.
     *
     * @param string $name
     *
     * @return Response
     */
    public function showIconAction($name)
    {
        $path = $this->locate('@AppSportBundle/Resources/public/icons/' . $name);
        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($path));
        $response->headers->set('Content-Disposition', 'inline; filename="' . $name . '";');
        $response->headers->set('Content-length', filesize($path));
        $response->sendHeaders();
        $response->setContent(readfile($path));

        return $response;
    }
}
