<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $hashedPassword = "password123";

        $user = (new User())
        ->setEmail('test@test.com')
        ->setPassword($hashedPassword)
        ->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();
    }
}
