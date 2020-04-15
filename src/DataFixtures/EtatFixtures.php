<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

      $etat = new Etat();
      $etat->setName('Créée');
      $manager->persist($etat);
      $this->setReference('etat_cree',$etat);

      $etat = new Etat();
      $etat ->setName('Ouverte');
      $manager->persist($etat);
      $this->setReference('etat_ouvert',$etat);

      $etat = new Etat();
      $etat->setName('Clôturée');
      $manager->persist($etat);
      $this->setReference('etat_cloture',$etat);

      $etat = new Etat();
      $etat->setName('Activité en cours');
      $manager->persist($etat);
      $this->setReference('etat_activite_en_cours',$etat);

      $etat = new Etat();
      $etat ->setName('Passée');
      $manager->persist($etat);
      $this->setReference('etat_passe',$etat);

      $etat = new Etat();
      $etat->setName('Annulée');
      $manager->persist($etat);
      $this->setReference('etat_annule',$etat);

      $manager->flush();
    }



}
