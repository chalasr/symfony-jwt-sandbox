<?php

namespace App\SportBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // return $this->redirect($this->generateUrl('sonata_admin_dashboard'));
        return new Response('API HOME');
    }

    public function callTestAction()
    {
        return new JsonResponse(['content' => 'hello']);
    }
}
