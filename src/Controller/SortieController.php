<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/CreerSortie", name="sortie_create")
     */
    public function createSortie()
    {
        return $this->render("sortie/creerSortie.html.twig");
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
