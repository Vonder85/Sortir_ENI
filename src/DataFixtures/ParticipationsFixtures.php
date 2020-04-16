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
