<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersCsvType;
use App\Repository\SiteRepository;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
    public function deleteUser($id, $csrf, UserRepository $ur, EntityManagerInterface $em){
        if($this->isGranted('ROLE_ADMIN')){
            if(!$this->isCsrfTokenValid('user_delete_' . $id, $csrf)){
                throw $this->createAccessDeniedException('Sorry, session has expired !');
            }else {
                $user = $ur->find($id);
                $em->remove($user);
                $em->flush();

                $this->addFlash('success', 'Utilisateur supprimé');
                return $this->redirectToRoute('admin_users');
            }
        }else {
            return $this->redirectToRoute('main_home');
        }
    }

    /**
     * @Route("/user/add/", name="user_add")
     */
    public function addOneUser(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder){
        if($this->isGranted('ROLE_ADMIN')){
            $user = new User();
            $user->setCreatedAt(new \DateTime());
            $user->setActive(true);
            $registerForm=$this->createForm(RegisterType::class, $user);
            $registerForm->handleRequest($request);
            if($registerForm->isSubmitted() && $registerForm->isValid()){
                $hashed = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hashed);
                $photo = $registerForm->get('photo')->getData();
                if($photo){
                    $photoName = $this->generateUniqueFileName().'.'.strtolower($photo->getClientOriginalExtension());
                    $photo->move(
                        $this->getParameter('upload_photos'),
                        $photoName
                    );
                    $user->setPhoto($photoName);
                }

                $role =
                dump($role);
                if($role == 0){
                    $roles[] = 'ROLE_ADMIN';
                }else{
                    $roles[] = 'ROLE_USER';
                }
                $user->setRoles($roles);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre utilisateur est bien ajouté');
                return $this->redirectToRoute('admin_home');
            }
            return $this->render('admin/addUser.html.twig',[
                "registerForm" => $registerForm->createView(),
            ]);

        }else {
            return $this->redirectToRoute('main_home');
        }
    }

    /**
     * @Route("users/add/", name="users_add")
     */
    public function addUsersWithCsv(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, SiteRepository $sr){
        if($this->isGranted('ROLE_ADMIN')){
            $csvForm = $this->createForm(UsersCsvType::class);

            $csvForm->handleRequest($request);
            if($csvForm->isSubmitted() && $csvForm->isValid()){
                $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

                $filecsv = $csvForm->get('csv')->getData();
                $data = $serializer->decode(file_get_contents($filecsv), 'csv');

                for($i = 0; $i < sizeof($data); $i++){
                    $role = [];
                    $role[0] = $data[$i]["roles"];
                    $user = new User();
                    //Hasher mot de passe
                    $hashed = $encoder->encodePassword($user, $data[$i]["password"]);

                    $date = new \DateTime($data[$i]['created_at']);
                    $site = $sr->find($data[$i]["site_id"]);

                    $user->setUsername($data[$i]["username"]);
                    $user->setLastname($data[$i]["lastname"]);
                    $user->setFirstname($data[$i]["firstname"]);
                    $user->setEmail($data[$i]["email"]);
                    $user->setPassword($hashed);
                    $user->setPhoto($data[$i]["photo"]);
                    $user->setActive($data[$i]['active']);
                    $user->setCreatedAt($date);
                    $user->setTelephone($data[$i]["telephone"]);
                    $user->setResetToken($data[$i]["reset_token"]);
                    $user->setSite($site);
                    $user->setRoles($role);

                    $em->persist($user);
                    $em->flush();

                    }
                $this->addFlash('success', sizeof($data).' utilisateurs ajoutés');
                return $this->redirectToRoute('admin_home');
            }

            return $this->render('admin/addUsersWithCsv.html.twig',[
                "csvForm" => $csvForm->createView(),
            ]);
        }else {
            return $this->redirectToRoute('main_home');
        }
    }

    /**
     * @Route("/desactiver/{id}/{csrf}", name="user_desactivate", requirements={"id": "\d+"})
     */
    public function desactivateUser($id, $csrf, UserRepository $ur, EntityManagerInterface $em){
        if($this->isGranted('ROLE_ADMIN')) {
            if(!$this->isCsrfTokenValid('user_desactivate_' . $id, $csrf)){
                throw $this->createAccessDeniedException('Sorry, session has expired !');
            }else {
                $user = $ur->find($id);
                $user->setActive(false);

                $em->flush();

                $this->addFlash('success', 'Utilisateur désactivé');
                return $this->redirectToRoute('admin_users');
            }
        }else{
            return $this->redirectToRoute('main_home');
        }

    }

    /**
     * @Route("/activer/{id}/{csrf}", name="user_activate", requirements={"id": "\d+"})
     */
    public function activateUser($id, $csrf, UserRepository $ur, EntityManagerInterface $em){
        if($this->isGranted('ROLE_ADMIN')) {
            if(!$this->isCsrfTokenValid('user_activate_' . $id, $csrf)){
                throw $this->createAccessDeniedException('Sorry, session has expired !');
            }else {
                $user = $ur->find($id);
                $user->setActive(false);

                $em->flush();

                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('admin_users');
            }
        }else{
            return $this->redirectToRoute('main_home');
        }

    }
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}


