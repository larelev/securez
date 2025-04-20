<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ApiAuthenticationTest extends ApiTestCase
{
    private ?string $token = null;
    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->createTestUser();
    }

    private function createTestUser(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => 'test@example.com']);
        
        if (!$existingUser) {
            $user = new User();
            $user->setEmail('test@example.com');
            $hasher = static::getContainer()->get('security.user_password_hasher');
            $user->setPassword($hasher->hashPassword($user, 'test123!'));
            $user->setRoles(['ROLE_USER']);
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function testLogin(): void
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $response->toArray());
        
        $this->token = $response->toArray()['token'];
    }

//    public function testApiAccessWithoutToken(): void
//    {
//        static::createClient()->request('GET', '/api');
//
//        $this->assertResponseStatusCodeSame(401);
//        $this->assertJsonContains(['message' => 'JWT Token not found']);
//    }
//
//    public function testApiAccessWithInvalidToken(): void
//    {
//        static::createClient()->request('GET', '/api', ['headers' => [
//            'Authorization' => 'Bearer invalid_token',
//        ]]);
//
//        $this->assertResponseStatusCodeSame(401);
//        $this->assertJsonContains(['message' => 'Invalid JWT Token']);
//    }
//
//    public function testApiAccessWithValidToken(): void
//    {
//        // First, get a valid token
//        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
//            'email' => 'test@example.com',
//            'password' => 'test123!',
//        ]]);
//
//        $token = $response->toArray()['token'];
//
//        // Then test API access
//        $response = static::createClient()->request('GET', '/api', ['headers' => [
//            'Authorization' => 'Bearer ' . $token,
//        ]]);
//
//        $this->assertResponseIsSuccessful();
//    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//
//        // Reset the entity manager
//        if ($this->entityManager) {
//            $this->entityManager->close();
//            $this->entityManager = null;
//        }
//    }
}