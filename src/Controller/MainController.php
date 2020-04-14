<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/Home", name="homePage")
     */
    public function homePage()
    {
        dump("oui");
        return $this->render("main/homePage.html.twig");
    }
}
