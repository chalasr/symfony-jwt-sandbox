<?php

namespace App\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Doctrine\ORM\EntityManager;

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
         $username = $event->getUser()->getUsername();
         $userManager = $this->em->getRepository('AppUserBundle:User');
         $fullUser = $userManager->findOneBy(['username' => $username]);

         $data['user'] = array(
             'id'     => $fullUser->getId(),
             'email'  => $fullUser->getEmail(),
             'group'  => $fullUser->getGroup() ? $fullUser->getGroup()->getName() : 'undefined',
             'roles'  => $fullUser->getRoles(),
         );

         $event->setData($data);
     }
 }
