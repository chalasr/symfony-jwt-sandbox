<?php

namespace App\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * JWTResponseListener.
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class JwtResponseListener
{
    /**
     * Add public data to the authentication response.
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['user'] = array(
            'id'         => $user->getId(),
            'username'   => $user->getUsername(),
            'first_name' => $user->getFirstname(),
            'last_name'  => $user->getLastname(),
            'email'      => $user->getEmail(),
            'roles'      => $user->getRoles(),
        );

        if (null !== $user->getFacebookId()) {
            $data['user']['facebook_id'] = $user->getFacebookId();
        }

        $event->setData($data);
    }
}
