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
            $sortie = new Sortie();
            $sortie->setName("Nom sortie ".$i);
            $sortie->setDateTimeStart(new \DateTime());
            $sortie->setDuration(rand(1,200));
            $sortie->setDeadlineRegistration(new \DateTime()); //strtotime(new \DateTime()."+ ".rand(1,15)." days")
            $sortie->setMaxNumberRegistration(rand(10,10000));
            $sortie->setDescription($this->getRandomDescription());
            $sortie->setOrganisateur($this->getReference("user".rand(0,5)));
            $manager->persist($sortie);
        }

        $manager->flush();
    }



    /**
     * @inheritDoc
     */
    public function getDependencies() {
        return array(
            UserFixtures::class
        );
    }

    private function getRandomDescription(){
        $rand=rand(0,5);
        switch ($rand){
            case 0:
                return "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut sodales nibh ut ante ullamcorper accumsan. Maecenas vehicula molestie mollis. Phasellus lorem ex, accumsan non congue id, mattis non diam. Sed at odio lacus. Nullam ex nisl, efficitur sed justo eu, maximus pulvinar felis. Mauris et elementum lorem, at sollicitudin felis. Maecenas vestibulum, enim in cursus rutrum, lacus ante scelerisque justo, a auctor dui ligula et felis. Sed convallis et tellus a vulputate. Curabitur pellentesque nunc pharetra est venenatis finibus. Curabitur ac eros vel urna suscipit vulputate sit amet id elit. Nulla congue velit vitae risus vestibulum, sed hendrerit elit rhoncus. ";
                break;
            case 1:
                return "Praesent sed mi euismod, interdum nunc eu, pharetra orci. Pellentesque et tristique elit. Cras tempus fermentum nisi, eget convallis justo luctus sit amet. Mauris commodo augue iaculis, congue sapien id, pulvinar ligula. Aenean rhoncus rutrum nisi sed volutpat. Fusce blandit massa lectus. Nulla quis nulla nec orci vestibulum dapibus. Quisque consectetur in velit a interdum. Nam sodales maximus lacus, id porta est rutrum a. Nullam lacinia nisi non dui fringilla pulvinar. Sed aliquet vulputate sapien ut venenatis. Nulla consectetur purus vel fermentum pharetra. Cras faucibus eu ipsum vel feugiat. Nullam in sapien non neque suscipit convallis nec quis lorem. Aliquam ac velit dolor. ";
                break;
            case 2:
                return "Sed vestibulum metus nec enim finibus ultrices. Proin nec urna non enim porta gravida et at quam. Integer auctor, mauris sollicitudin molestie egestas, ipsum dui imperdiet ante, in feugiat diam eros eget magna. Morbi in eros sodales, ornare odio a, aliquet massa. Mauris lacinia libero ac orci ornare, ac volutpat nunc ultrices. Vestibulum eget nisi in ex rutrum faucibus. Vestibulum neque dui, pellentesque eu accumsan eu, malesuada cursus eros. Cras vestibulum convallis hendrerit. Donec blandit egestas dolor, nec feugiat nisi malesuada at. Fusce molestie congue ipsum, sit amet faucibus est lacinia eget. ";
                break;
            case 3:
                return "Aliquam ultricies laoreet commodo. Etiam nec nisl quis felis ultricies eleifend ut non risus. Morbi vel lacus ligula. Integer tempus non quam ac tempus. Donec dictum vel diam id condimentum. Aliquam rhoncus tellus venenatis massa vulputate molestie. Cras non iaculis dui. Pellentesque ultrices quam vel diam malesuada bibendum. ";
                break;
            default:
                return "description par défaut, ne devrait pas apparaitre";
                break;
        }
    }
}