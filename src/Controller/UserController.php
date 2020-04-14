<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/Inscription", name="user_register")
     */
    public function register()
    {
        return $this->render();
    }

    /**
     * @Route("/Connexion", name="login")
     */
    public function login(){
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/Deconnexion", name="logout")
     */
    public function logout(){}

}
