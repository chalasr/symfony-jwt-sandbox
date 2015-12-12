<?php

namespace App\SportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // return $this->redirect($this->generateUrl('sonata_admin_dashboard'));
        return $this->render('AppUserBundle:Default:list.html.twig');
    }

    public function getUsersListAction()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $query = $repo->createQuerybuilder('u')
        ->select('u.username', 'u.email')
        ->getQuery();
        // $toJson = [];
        // foreach ($users as $u) {
        //     $toJson[  ] = [
        //         'username' => $u->getUsername(),
        //         'email'    => $u->getEmail(),
        //     ];
        // }

        return new JsonResponse($query->getResult());
    }

    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
