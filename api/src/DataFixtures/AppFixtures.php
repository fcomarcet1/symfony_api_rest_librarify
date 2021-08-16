<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordEncoder;

    public function __construct(UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(Uuid::uuid4(), 'user@user.com');
        $password = $this->userPasswordEncoder->hashPassword($user, 'password');

        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(false);
        $user->setCreatedAt(new DateTime('now'));
        $user->setUpdatedAt(null);

        $manager->persist($user);
        $manager->flush();
    }
}
