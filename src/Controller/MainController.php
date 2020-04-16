<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Data\SortiesCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function homePage(EntityManagerInterface $em, Request $req)
    {
        $sortiesCriteria = $this->buildCriteria($req);
        //$sorties = $em->getRepository(Sortie::class)->findSortiesFiltered($sortiesCriteria);
        $sites = $em->getRepository(Site::class)->findAll();
        return $this->render("main/homePage.html.twig", [
            "sorties" => null,
            "sites" => $sites
        ]);
    }

    public function buildCriteria($req){
        $sortiesCriteria = new SortiesCriteria();
        if($req->query->get('selectSite')!=""){
            $sortiesCriteria->setSite($req->query->get('selectSite'));
        }
        if($req->query->get('textSearch')){
            $sortiesCriteria->setSearch($req->query->get('textSearch'));
        }
        if($req->query->get('dateDebut')){
            $ymd = \DateTime::createFromFormat('Y-m-d', $req->query->get('dateDebut'))->format('d/m/Y');

            $sortiesCriteria->setDateDebut($ymd);
        }
        if($req->query->get('dateFin')){

        }
        if($req->query->get('checkbox1')){
            $sortiesCriteria->setOrganisateur(true);
        }
        if($req->query->get('checkbox2')){
            $sortiesCriteria->setInscrit(true);
        }
        if($req->query->get('checkbox3')){
            $sortiesCriteria->setPasInscrit(true);
        }
        if($req->query->get('checkbox4')){
            $sortiesCriteria->setSortiePassee(true);
        }
        dump($sortiesCriteria);
    }
}
