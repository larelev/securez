<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $password = hash('md5', uniqid(mt_rand(1, mt_getrandmax()), true));
        $user = (new User())
            ->setEmail('david@exameple.com')
            ->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
