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
        for ($i = 0; $i < 10; $i++){
            $lieu = new Lieu();
            $lieu ->setName($this->RandomNomLieu());
            $lieu->setStreet($this->RandomRueLieu());
            $lieu->setLatitude(rand(11201,15055)/100);
            $lieu->setLongitude(rand(14012,45682)/100);
            $lieu->setVille($this->getReference("ville".rand(0,9)));
            $this->addReference("lieu".$i,$lieu);
            $manager->persist($lieu);

        }

        $manager->flush();
    }

    public function RandomNomLieu()
    {
        $rand = rand(0, 10);
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
            case 6 :
                return "Concert";
                break;
            case 7 :
                return "Spa";
                break;
            case 8 :
                return "Plage";
                break;
            case 9 :
                return "Espace Quilly";
                break;
            case 10 :
                return "Mont Saint Michel";
                break;

            default:
                return "Pas de lieu sélectionné";

        }
    }


        public function RandomRueLieu()
        {
            $rand = rand(0, 10);
            switch ($rand) {
                case 0 :
                    return "rue de la paix";
                    break;
                case 1 :
                    return "Rue de la République";
                    break;
                case 2 :
                    return "Rue de Bourgogne";
                    break;
                case 3 :
                    return "Allée Artistide Briand";
                    break;
                case 4 :
                    return "Avenue Foch";
                    break;
                case 5 :
                    return "Boulevard Marcel Paul";
                    break;
                case 6 :
                    return "Allée de la gare";
                    break;
                case 7 :
                    return "Rue Benjamin Franklin";
                    break;
                case 8 :
                    return "Quai Branly";
                    break;

                case 9 :
                    return "Place de la Victoire";
                    break;
                case 10 :
                    return "Avenue des Champs Elysées";
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
