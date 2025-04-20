<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class JWTResponseListener
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $response = $event->getResponse();
        $data = $event->getData();
        $token = $data['token'];

        $response->headers->setCookie(
            new Cookie(
                'BEARER',           // Cookie name
                $token,             // Cookie value
                time() + 3600,      // Expiration
                '/',               // Path
                null,              // Domain
                false,             // Secure
                true,              // httpOnly
                false,             // raw
                'lax'              // SameSite
            )
        );
    }
}