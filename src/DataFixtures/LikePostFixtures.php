<?php

namespace App\DataFixtures;

use App\Entity\LikePost;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LikePostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) 
        {
        $faker = Faker\Factory::create('fr_FR');
        $likePost = new LikePost();
        $likePost -> setUser($faker -> uniqueId()->ramdomDigitNotNull());
        $likePost -> setPost ($faker ->post);

        $manager->persist($likePost);
        $manager->flush();
        }
    }
}
