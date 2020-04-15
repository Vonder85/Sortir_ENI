<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++){
            $lieu = new Lieu();
            $lieu ->setName($this->RandomNomLieu());
            $lieu->setStreet($this->RandomRueLieu());
            $lieu->setLatitude(rand(11201,15055)/100);
            $lieu->setLongitude(rand(14012,45682)/100);
            $lieu->setVille($this->getReference("ville1"));
            $this->addReference("lieu".$i,$lieu);
            $manager->persist($lieu);

        }

        $manager->flush();
    }

    public function RandomNomLieu()
    {
        $rand = rand(0, 5);
        switch ($rand) {
            case 0 :
                return "Bar";
                break;
            case 1 :
                return "Cinéma";
                break;
            case 2 :
                return "Escape Game";
                break;
            case 3 :
                return "Paintball";
                break;
            case 4 :
                return "Billard";
                break;
            case 5 :
                return "Restaurant";
                break;
            default:
                return "Pas de lieu sélectionné";

        }
    }


        public function RandomRueLieu()
        {
            $rand = rand(0, 5);
            switch ($rand) {
                case 0 :
                    return "rue de la paix";
                    break;
                case 1 :
                    return "Rue de la République";
                    break;
                case 2 :
                    return "rue de Bourgogne";
                    break;
                case 3 :
                    return "allée Artistide Briand";
                    break;
                case 4 :
                    return "Avenue Foch";
                    break;
                case 5 :
                    return "Boulevard Marcel Paul";
                    break;
                default:
                    return "Pas de rue sélectionnée";

            }
        }

    public function getDependencies()
    {
        return array(
            VilleFixtures::class
        );
    }

}
