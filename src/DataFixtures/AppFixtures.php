<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\TaskFactory;
use App\Factory\ToolsFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {

        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('example@account.com')
            ->setRoles(['ROLE_USER'])
            ->setIsVerified(true);

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, 'enter');
        $user->setPassword($hashedPassword);

        TaskFactory::createMany(5, function () use ($user) {
            return [
                'user' => $user
            ];
        });

        UserFactory::createMany(10);
        ToolsFactory::createMany(10);

        TaskFactory::createMany(100, function () {
            return [
                'user' => UserFactory::random(),
                'tools' =>[ToolsFactory::random()]
            ];
        });

        $manager->persist($user);
        $manager->flush();
    }
}
