<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use App\Entity\TypeRecette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($j=0;$j<6;$j++) {
            $typeR = new TypeRecette();
            $typeR->setNom("type".$j);
            for ($i=0;$i<10;$i++) {
                $recette = new Recette();
                $recette->setNom("produit".$i);
                $recette->setDescription("La recette".$i." est facile");
                $recette->setImage("img");
                $recette->setTypeRecette($typeR);
                $manager->persist($recette);
            }
            $manager->persist($typeR);
        }
        $manager->flush();
    }
}
