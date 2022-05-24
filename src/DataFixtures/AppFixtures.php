<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\TypeRecette;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher=$passwordHasher;
    }
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


        for($i=0;$i<2;$i++) 
        {
            $user=new User();
            $user->setNom('user'.$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getNom()
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        for($i=3;$i<5;$i++) 
        {
            $user=new User();
            $user->setNom('user'.$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getNom()
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
        }



        $manager->flush();
    }
}
