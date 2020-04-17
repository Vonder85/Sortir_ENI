<?php

namespace App\DataFixtures;

use App\Entity\Participations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipationsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i=0;$i<100;$i++){
            $participant = rand(0,5);
            //com sa dé foa ya pa de participen
            if($participant<5){
                    $participation = new Participations();
                    $participation->setSortie($this->getReference("sortie".$i));
                    $participation->setUser($this->getReference("user".$participant));
                    $manager->persist($participation);
                    //com sa de foi ya plusieur parrtucoze
                    if($participant%2==0){
                        $participation = new Participations();
                        $participation->setSortie($this->getReference("sortie".$i));
                        if($participant>3){
                            $participation->setUser($this->getReference("user".($participant-1)));
                        }else{
                            $participation->setUser($this->getReference("user".($participant+1)));
                        }
                        $manager->persist($participation);
                    }
                }

        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies() {
        return array(
            SortieFixtures::class,
            UserFixtures::class
        );
    }
}
