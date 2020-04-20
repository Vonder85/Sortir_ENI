<?php

namespace App\Controller;

use App\Entity\Participations;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Data\SortiesCriteria;
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
    public function homePage(EntityManagerInterface $em, Request $req)
    {

        if (!$this->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirectToRoute('Connexion');
        } else {
            if ($this->isActive()) {
                $sortiesCriteria = $this->buildCriteria($req, $em);
                $sorties = $em->getRepository(Sortie::class)->findSortiesFiltered($sortiesCriteria);
                dump($sorties);
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
}
