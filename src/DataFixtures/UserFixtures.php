<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;


class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setFirstname('samir');
        $admin->setLastname('Yakhlef');
        $admin->setUsername('rayzaan');
        $admin->setEmail('samir.yakhlef30@gmail.com');
        $admin->setRoles(['admin']);
        $admin->setPassword('adminpwd');

        $manager->persist($admin);
        $manager->flush();
    }
}
