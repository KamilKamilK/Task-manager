<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        UserFactory::createOne()
            ->setEmail('example@account.com')
            ->setPassword('enter')
            ->setRoles(['ROLE_USER'])
            ->setIsVerified(true);

        UserFactory::createMany(10);

        TaskFactory::createMany(100, function () {
            return [
                'user' => UserFactory::random()
            ];
        });

        $manager->flush();
    }
}
