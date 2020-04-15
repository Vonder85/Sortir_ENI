<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/CreerSortie", name="sortie_create")
     */
    public function createSortie(Request $request,EntityManagerInterface $em)
    {
        $sortie = new Sortie();
        $sortForm=$this->createForm(SortieType::class,$sortie);
        $sortForm->handleRequest($request);
        if ($sortForm->isSubmitted()&& $sortForm->isValid())
        {
            $sortie->setOrganisateur($this->getUser());
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success','Votre sortie est bien créée ');
            return $this->redirectToRoute('main_home');
        }

        return $this->render("sortie/creerSortie.html.twig",[
            'sortForm'=>$sortForm->createView()
        ]);
    }

    /**
     * @Route("/ModifierSortie", name="sortie_modify")
     */
    public function modifySortie()
    {
        return $this->render("sortie/modifierSortie.html.twig");
    }

    /**
     * @Route("/Sortie", name="sortie_consult")
     */
    public function consultSortie()
    {
        return $this->render("sortie/consultSortie.html.twig");
    }

    /**
     * @Route("/AnnulerSortie", name="sortie_cancel")
     */
    public function cancelSortie()
    {
        return $this->render("sortie/annulerSortie.html.twig");
    }
}
