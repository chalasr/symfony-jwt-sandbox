<?php

namespace App\Util\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractRestController extends Controller
{
    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
