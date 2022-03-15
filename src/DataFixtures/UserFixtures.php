<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;
class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < 5; $i++) {

        $admin = new User();
        $admin->setFirstname($faker->firstName);
        $admin->setLastname($faker->lastName);
        $admin->setUsername($faker->username);
        $admin->setEmail($faker->email);
        $admin->setRoles(['admin']);
        $admin->setPassword($faker->password);

        $manager->persist($admin);
        $manager->flush();
        }
    }
}
