<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation as Http;

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
     * @param string|int $sport
     *
     * @return Response
     */
    public function showIconAction($sport)
    {
        $repo = $this->getDoctrine()->getRepository('AppSportBundle:Sport');
        $sport = is_numeric($sport)
        ? $repo->findOrFail($sport)
        : $repo->findOneByOrFail(['name' => $sport]);

        $iconName = $sport->getIcon();
        $path = $this->locate('@AppSportBundle/Resources/public/icons/'.$iconName);
        $response = new Http\Response();
        $response->headers->set('Content-type', mime_content_type($path));
        $response->headers->set('Content-Disposition', 'inline; filename="'.$sport.'";');
        $response->headers->set('Content-length', filesize($path));
        $response->sendHeaders();
        $response->setContent(readfile($path));

        return $response;
    }
}
