<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpParser\Node\Stmt\Case_;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $i=0;

        $ville = new Ville();
        $ville->setName("Bordeaux");
        $ville->setZip("33000");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Nantes");
        $ville->setZip("44000");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Lille");
        $ville->setZip("59000");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Marseille");
        $ville->setZip("13001");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Paris");
        $ville->setZip("75001");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Ajaccio");
        $ville->setZip("20000");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Lyon");
        $ville->setZip("69000");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Nice");
        $ville->setZip("06100");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("La Rochelle");
        $ville->setZip("17000");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $ville = new Ville();
        $ville->setName("Nancy");
        $ville->setZip("54000");
        $this->addReference("ville".$i,$ville);
        $manager->persist($ville);
        $i++;

        $manager->flush();
    }

}