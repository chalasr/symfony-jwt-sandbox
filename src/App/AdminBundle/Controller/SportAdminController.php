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
        $entity = is_numeric($sport)
        ? $repo->findOrFail($sport)
        : $repo->findOneByOrFail(['name' => $sport]);
        $iconName = $entity->getIcon() ?: 'default.png';
        //
        // if (!$iconName) {
        //     $iconName = 'default.png';
        // }

        $path = $this->locateResource('@AppSportBundle/Resources/public/icons/'.$iconName);
        $iconInfo = pathinfo($path);

        if (false === isset($iconInfo['extension'])) {
            $path = $this->locateResource('@AppSportBundle/Resources/public/icons/default.png');
        }

        $response = new Http\Response();
        $response->headers->set('Content-type', mime_content_type($path));
        $response->headers->set('Content-length', filesize($path));
        $response->sendHeaders();
        $response->setContent(file_get_contents($path));

        return $response;
    }
}
