<?php

namespace App\Util\Controller;

use App\Util\DependencyInjection\LocalizableTrait as Localizable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractRestController extends Controller
{
    use Localizable;

    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
