<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api')]
class LoginController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface $jwtManager
    ) {}

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    #[OA\Post(
        path: '/api/login',
        description: 'Login user and get JWT token',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'refresh_token', type: 'string')
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials')
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Missing credentials'], 400);
        }

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        if (!$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse([
            'token' => $token,
            'refresh_token' => null, // Implement refresh token if needed
            'user' => [
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }
}
