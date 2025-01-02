<?php

namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $jean = new User();
        $jean->setEmail('jean@example.com');
        $jean->setPassword($this->passwordHasher->hashPassword($jean, 'password'));
        $manager->persist($jean);

        $manager->flush();
    }
}
