<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participations;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Data\SortiesCriteria;
use App\Repository\EtatRepository;
use App\Repository\ParticipationsRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController

{

    /**
     * @Route("/", name="main_home")
     */
    public function homePage(EntityManagerInterface $em, Request $req, EtatRepository $er, ParticipationsRepository $pr)
    {

        if (!$this->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirectToRoute('Connexion');
        } else {
            if ($this->isActive()) {
                $this->etat($er, $em, $pr);
                $sortiesCriteria = $this->buildCriteria($req, $em);
                $sorties = $em->getRepository(Sortie::class)->findSortiesFiltered($sortiesCriteria);
                $sites = $em->getRepository(Site::class)->findAll();
                $userSorties = $em->getRepository(Participations::class)->findByUserId($this->getUser());

                return $this->render("main/homePage.html.twig", [
                    "sorties" => $sorties,
                    "sites" => $sites,
                    "sortiesCriteria" => $sortiesCriteria,
                    "userSorties" => $userSorties
                ]);

            } else {
                return $this->render('user/desactivate.html.twig');
            }
        }
    }

    public function buildCriteria(Request $req, EntityManagerInterface $em)
    {
        $sortiesCriteria = new SortiesCriteria();
        if ($req->query->get('selectSite') != "" && $req->query->get('selectSite') != null) {
            if ($req->query->get('selectSite') == "all") {

            } else {
                $idSite = $req->query->get('selectSite');
                $site = $em->getRepository(Site::class)->find($idSite);
                $sortiesCriteria->setSite($site);
            }
        } else {
            $sortiesCriteria->setSite($this->getUser()->getSite());
        }
        if ($req->query->get('textSearch')) {
            $sortiesCriteria->setSearch($req->query->get('textSearch'));
        }
        if ($req->query->get('dateDebut')) {
            $date = new \DateTime($req->query->get('dateDebut'));
            $sortiesCriteria->setDateDebut($date);
        }
        if ($req->query->get('dateFin')) {
            $date = new \DateTime($req->query->get('dateFin'));
            $sortiesCriteria->setDateFin($date);
        }
        if ($req->query->get('checkbox1')) {
            $sortiesCriteria->setOrganisateur(true);
        }
        if ($req->query->get('checkbox2')) {
            $sortiesCriteria->setInscrit(true);
        }
        if ($req->query->get('checkbox3')) {
            $sortiesCriteria->setPasInscrit(true);
        }
        if ($req->query->get('checkbox4')) {
            $sortiesCriteria->setSortiePassee(true);
        }
        return $sortiesCriteria;
    }

    public function isActive()
    {
        if ($this->getUser()->getActive()) {
            return true;
        } else {
            return false;
        }
    }

    public function etat(EtatRepository $er, EntityManagerInterface $em, ParticipationsRepository $pr)
    {
        $etats = $er->findAll();
        $sorties = $em->getRepository(Sortie::class)->findAll();
        $dateToday = new \DateTime();
        $now = new \DateTime($dateToday->format('Y-m-d H:i:s'));
        foreach ($sorties as $sortie){

            $date = $sortie->getDateTimeStart();
            $dateDebut = new \DateTime($date->format('Y-m-d H:i:s'));
            $dateFin = $dateDebut->add(new \DateInterval('PT0H'.$sortie->getDuration().'M'))->format('Y-m-d H:i:s');

            /**
             * var Sortie $sortie
             */

            if(($sortie->getDeadlineRegistration() > $now && $sortie->getDateTimeStart() > $now)){
            }
            elseif($sortie->getDateTimeStart()< $now && $dateFin > $now ){
                $sortie->setEtat($etats[3]);
            }elseif (($sortie->getDeadlineRegistration() < $now && $sortie->getDateTimeStart() > $now) /*|| $pr->findNbParticipations($sortie->getId()) == $sortie->getMaxNumberRegistration()*/){
                $sortie->setEtat($etats[2]);
            }
            elseif($dateFin < $now){
                $sortie->setEtat($etats[4]);
            }else{
                $sortie->setEtat($etats[5]);
            }
        }

        $em->flush();

    }
}
