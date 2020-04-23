<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{
      public function load(ObjectManager $manager)
    {
        $i=0;

            $site = new Site();
            $site->setName("Nantes");
            $this->addReference("site".$i, $site);
            $manager->persist($site);
            $i++;

             $site = new Site();
             $site->setName("Rennes");
             $this->addReference("site".$i, $site);
             $manager->persist($site);
             $i++;

             $site = new Site();
             $site->setName("Niort");
             $this->addReference("site".$i, $site);
             $manager->persist($site);
             $i++;


        $manager->flush();
    }

}
