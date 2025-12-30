<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
    

        $admin = new User();
        $admin->setUsername('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('User');
        $user->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
