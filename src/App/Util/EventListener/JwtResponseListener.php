<?php

namespace App\Util\EventListener;

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
         $username = $event->getUser() ? $event->getUser()->getUsername() : '';
         $userManager = $this->em->getRepository('AppUserBundle:User');
         $user = $userManager->findOneBy(['username' => $username]);

         $data['user'] = array(
             'id'     => $user->getId(),
             'email'  => $user->getEmail(),
             'group'  => $user->getFullGroup(),
             'roles'  => $user->getRoles(),
         );

         $event->setData($data);
     }
 }
