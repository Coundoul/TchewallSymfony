<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jobs=[
            "Data scientist",
            "Statisticien",
            "Analyste cyber-sécurité",
            "Médecin ORL",
            "Échographiste",
            "Mathématicien",
            "Ingénieur logiciel",
            "Analyste informatique",
            "Pathologiste du discours / langage",
            "Actuaire",
            "Ergothérapeute",
            "Directeur des Ressources Humaines",
            "Hygiéniste dentaire",
            "Ingénieur biomédical",
            "Diététicien",
            "Météorologue",
            "Administrateur système",
            "Ophtalmologue"
        ];

        for ($i=0; $i<count($jobs); $i++)
        {
             $jobsS = new Job();
             $jobsS->setDesignation($jobs[$i]);
             $manager->persist($jobsS);
        }

        $manager->flush();
    }
}
