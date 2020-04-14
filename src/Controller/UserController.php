<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/Inscription", name="user_register")
     */
    public function register(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setCreatedAt(new \DateTime());

        $registerForm=$this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()){
            //Hash password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('main_home');
        }
        return $this->render("user/register.html.twig", [
            "registerForm" => $registerForm->createView(),
        ]);
    }

    /**
     * @Route("/Connexion", name="login")
     */
    public function login(){
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/Profil", name="user_profile")
     */
    public function userProfile(){
        return $this->render('user/profile.html.twig');
    }

    /**
     * @Route("/Deconnexion", name="logout")
     */
    public function logout(){}

}
