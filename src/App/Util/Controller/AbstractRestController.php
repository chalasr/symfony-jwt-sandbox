<?php

namespace App\Util\Controller;

use App\Util\Controller\LocalizableTrait as Localizable;
use App\Util\Controller\EntitySerializable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractRestController extends Controller
{
    use Localizable, EntitySerializable;

    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function getRolesManager()
    {
        return $this->container->get('security.authorization_checker');
    }
}
