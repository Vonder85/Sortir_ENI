<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i=0;$i<100;$i++){
            $dateDebut = $this->getRandomDate();
            $sortie = new Sortie();
            $sortie->setName("Sortie n°".$i);
            $sortie->setDateTimeStart($dateDebut);
            $sortie->setDuration(rand(1,200));
            $sortie->setDeadlineRegistration(date_add(new \DateTime(), date_interval_create_from_date_string(rand(1,3)." days"))); //strtotime(new \DateTime()."+ ".rand(1,15)." days")
            $sortie->setMaxNumberRegistration(rand(10,40));
            $sortie->setDescription($this->getRandomDescription());
            $sortie->setOrganisateur($this->getReference("user".rand(0,8)));
            $sortie->setEtat($this->getRandomEtat());
            $sortie->setLieu($this->getReference("lieu".rand(0,9)));
            $sortie->setSite($this->getReference("site".rand(0,2)));
            $this->addReference("sortie".$i, $sortie);
            $manager->persist($sortie);
        }

        $manager->flush();
    }



    /**
     * @inheritDoc
     */
    public function getDependencies() {
        return array(
            UserFixtures::class,
            EtatFixtures::class,
            LieuFixtures::class
        );
    }

    private function getRandomDescription(){
        $rand=rand(0,4);
        switch ($rand){
            case 0:
                return "Idéal pour passer un bon moment entre amis et décompresser";
                break;
            case 1:
                return "Râleurs s'abstenir merci ";
                break;
            case 2:
                return "Petite sortie pour apprendre à mieux se connaitre et créer des liens, merci de vous inscrire rapidement ";
                break;
            case 3:
                return "Allez on lâche le taf et on s'éclate entre nous, les profs peuvent venir aussi! ";
                break;
            default:
                return "description par défaut, ne devrait pas apparaitre";
                break;
        }
    }

    private function getRandomEtat(){
        $etat=null;
        $rand=rand(0,1);
        switch ($rand){
            case 0 :
                $etat=$this->getReference("etat_cree");
                break;
            case 1 :
                $etat=$this->getReference("etat_ouvert");
                break;

        }

        return $etat;

    }

    private function getRandomDate() {
        $randDate = rand(0,5);
        $date = new \DateTime();
        switch($randDate){
            case 0:
                date_add($date, date_interval_create_from_date_string('10 days'));
                break;
            case 1:
                date_add($date, date_interval_create_from_date_string('-1 days'));
                break;
            case 2:
                date_add($date, date_interval_create_from_date_string('-3 days'));
                break;
            case 3:
                date_add($date, date_interval_create_from_date_string('6 days'));
                break;
            case 4:
                date_add($date, date_interval_create_from_date_string('8 days'));
                break;

        }
        return $date;
    }


}
