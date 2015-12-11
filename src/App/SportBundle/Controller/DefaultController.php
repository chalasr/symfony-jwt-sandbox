<?php

namespace App\SportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // return $this->redirect($this->generateUrl('sonata_admin_dashboard'));
        return new Response('API HOME');
    }
}
