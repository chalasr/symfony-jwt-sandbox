<?php

namespace App\Util\Controller;

use App\Util\Controller\EntitySerializableTrait as EntitySerializable;
use App\Util\Controller\LocalizableTrait as Localizable;
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

    protected function isAdmin()
    {
        return $this->getRolesManager()->isGranted('ROLE_ADMIN');
    }

    protected function isGuest()
    {
        $rolesManager = $this->getRolesManager();

        return $rolesManager->isGranted('ROLE_GUEST') && !$rolesManager->isGranted('ROLE_ADMIN');
    }
}
