<?php

namespace Util\Security\EventListener;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Util\Controller\CanSerializeTrait as CanSerialize;

/**
 * JWT Response listener.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class JwtResponseListener
{
    use CanSerialize;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Add public data to the authentication response.
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $username = $event->getUser() ? $event->getUser()->getUsername() : '';
        $userManager = $this->em->getRepository('UserBundle:User');
        $user = $userManager->findOneBy(['username' => $username]);

        $data['user'] = array(
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
        );

        $event->setData($data);
    }
}
