<?php

namespace App\Controller;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function homePage(EntityManagerInterface $em)
    {
        $sorties = $em->getRepository(Sortie::class)->findAll();
        return $this->render("main/homePage.html.twig", [
            "sorties" => $sorties,
        ]);
    }
}
