<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ProduitFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++){
            $produit = new Produit();
            $produit
                ->setNom($faker->words(3, true))
                ->setDescription($faker->sentence(2,true))
                ->setPrix($faker->numberBetween(50,500))
                ->setInventaire($faker->numberBetween(1,5));
            $manager->persist($produit);
        }
        $manager->flush();
    }
}
