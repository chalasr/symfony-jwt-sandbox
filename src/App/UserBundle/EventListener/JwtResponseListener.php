<?php

namespace App\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

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

        $data['user'] = array(
            'username'   => $user->getUsername(),
            'roles'      => $user->getRoles(),
        );

        $event->setData($data);
    }
}
