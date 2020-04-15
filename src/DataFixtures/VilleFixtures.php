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
        for ($i = 0; $i < 5; $i++) {
            $ville = new Ville();
            $ville->setName($this->RandomNomVille());
            $ville->setZip(rand(00001, 99999));
            $this->addReference("ville".$i,$ville);
            $manager->persist($ville);
        }

        $manager->flush();
    }

    public function RandomNomVille()
    {
        $rand = rand(0, 5);
        switch ($rand) {
            case 0 :
                return "Bordeaux";
                break;
            case 1 :
                return "Nantes";
                break;
            case 2 :
                return "Lille";
                break;
            case 3 :
                return "Marseille";
                break;
            case 4 :
                return "Paris";
                break;
            case 5 :
                return "Ajaccio";
                break;
            default:
                return "Pas de ville sélectionnée";

        }
    }
}