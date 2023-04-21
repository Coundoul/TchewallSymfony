<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $profile = new Profile();
         $profile->setRs('Instagram');
         $profile->setUrl('abdoulaye.intagram98');
         $manager->persist($profile);

        $profile1 = new Profile();
        $profile1->setRs('Facebook');
        $profile1->setUrl('abdoulaye.facebook98');
        $manager->persist($profile1);

        $profile2 = new Profile();
        $profile2->setRs('Twitter');
        $profile2->setUrl('abdoulaye.twitter98');
        $manager->persist($profile2);

        $manager->flush();
    }
}
