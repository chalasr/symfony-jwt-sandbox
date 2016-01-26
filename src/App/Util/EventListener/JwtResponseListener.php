<?php

namespace App\Util\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;

/**
* JWTResponseListener.
*
* @author Robin Chalas <rchalas@sutunam.com>
*/
class JwtResponseListener
{
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
        $userManager = $this->em->getRepository('AppUserBundle:User');

        $user = $userManager->findOneBy(['username' => $username]);
        $serializer = SerializerBuilder::create()->build();
        $context = SerializationContext::create()
            ->setGroups(array('api'))
            ->setSerializeNull(true);
        $user = json_decode($serializer->serialize($user, 'json', $context));
        $data['user'] = $user;

        $event->setData($data);
     }
}
