<?php

namespace Util\Security;

use Util\Controller\CanSerializeTrait as CanSerialize;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

/**
 * JWT Response listener.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class JwtResponseListener
{
