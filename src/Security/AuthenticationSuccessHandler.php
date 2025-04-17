<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private string $cookieName = 'JWT',
        private bool $cookieSecure = false,
        private ?string $cookieDomain = null,
        private int $cookieExpire = 3600,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $jwt = $this->jwtManager->create($token->getUser());

        $response = new Response('', Response::HTTP_FOUND);
        $response->headers->set('Location', '/api');

        $response->headers->setCookie(
            new Cookie(
                $this->cookieName,
                $jwt,
                time() + $this->cookieExpire,
                '/',
                $this->cookieDomain,
                $this->cookieSecure,
                true,
                false,
                Cookie::SAMESITE_LAX
            )
        );

        return $response;
    }
}
