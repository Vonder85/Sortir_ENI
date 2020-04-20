<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('admin/home.html.twig');
        }else{
            return $this->redirectToRoute('main_home');
        }

    }

    /**
     * @Route("/users", name="users")
     */
    public function users(UserRepository $ur){
        if($this->isGranted('ROLE_ADMIN')){
            $users = $ur->findAll();

            return $this->render('admin/users.html.twig', [
                'users' => $users,
            ]);
        }else {
            return $this->redirectToRoute('main_home');
        }

    }

    /**
     * @Route("/user/delete/{id}/{csrf}", name="user_delete", requirements={"id": "\d+"})
     */
    public function deleteUser($id, $csrf, UserRepository $ur){
        if($this->isGranted('ROLE_ADMIN')){
            if(!$this->isCsrfTokenValid('user_delete_' . $id, $csrf)){
                throw $this->createAccessDeniedException('Sorry, session has expired !');
            }else {
                $user = $ur->find($id);
            }
        }else {
            return $this->redirectToRoute('main_home');
        }
    }

    /**
     * @Route("/user/add/", name="user_add")
     */
    public function addOneUser(){
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('admin/addUser.html.twig');
        }else {
            return $this->redirectToRoute('main_home');
        }
    }

    /**
     * @Route("users/add/", name="users_add")
     */
    public function addUsersWithCsv(){
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('admin/addUsersWithCsv.html.twig');
        }else {
            return $this->redirectToRoute('main_home');
        }
    }
}


