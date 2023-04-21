<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $listHobbies=[
            "Le Yoga",
            "Les sports extrêmes",
            "La production de vidéo",
            "Les sports d’endurance",
            "Capitaine d’une équipe de sport",
            "Tenir un blog",
            "L’alpinisme",
            "Jouer d’un instrument",
            "Le volontariat",
            "La photographie",
            "Quelque chose d’inhabituel (dans le bon sens)",
            "Ce que nous ne devons absolument pas mettre : la lecture"
        ];
        for ($i=0; $i<count($listHobbies); $i++){
             $hobbies = new Hobby();
             $hobbies->setDesignation($listHobbies[$i]);
             $manager->persist($hobbies);
        }


        $manager->flush();
    }
}
