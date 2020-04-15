<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i<50; $i++){
            $site = new Site();
            $site->setName("Nom Site".$i);
            $site->setSortie($this->getReference(SortieFixtures::SORTIE_REFERENCE));
            $site->setParticipant($this->getReference(UserFixtures::USER_REFERENCE));
            $manager->persist($site);
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            SiteFixtures::class,
            UserFixtures::class,
            SortieFixtures::class,
        );
    }
}
