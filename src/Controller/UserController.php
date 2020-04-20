<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/Inscription", name="user_register")
     */
    public function register(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setCreatedAt(new \DateTime());
        $user->setActive(true);

        $registerForm=$this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()){
            //Hash password
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



            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('main_home');
        }
        return $this->render("user/register.html.twig", [
            "registerForm" => $registerForm->createView(),
        ]);
    }

    /**
     * @Route("/Connexion", name="Connexion")
     */
    public function login(Request $request, AuthenticationUtils $au){
            $error = $au->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $au->getLastUsername();
            return $this->render('user/login.html.twig', [
                "error" => $error,
                "lastusername" => $lastUsername,
            ]);
    }

    /**
     * @Route("/Profil/{id}", name="user_profile", requirements={"id": "\d+"})
     */
    public function userProfile($id, EntityManagerInterface $em, UserRepository $ur, Request $request, UserPasswordEncoderInterface $encoder){
        $user = $ur->find($id);
        $profileForm = $this->createForm(RegisterType::class, $user);
        $photoIn = $user->getPhoto();

        $profileForm->handleRequest($request);
        if($profileForm->isSubmitted() && $profileForm->isValid()){
            //Hash password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $photo = $profileForm->get('photo')->getData();
            if($photo){
                $photoName = $this->generateUniqueFileName().'.'.strtolower($photo->getClientOriginalExtension());
                $photo->move(
                    $this->getParameter('upload_photos'),
                    $photoName
                );
                $user->setPhoto($photoName);
            }else{
                $user->setPhoto($photoIn);
            }
            $em->flush();
        }
        return $this->render('user/profile.html.twig', [
            "profileForm" => $profileForm->createView(),
        ]);
    }

    /**
     * @Route("/Deconnexion", name="Deconnexion")
     */
    public function logout(){}

    /**
     * @Route("/MotdepasseOublie", name="user_forgotten_password")
     */
    public function forgottenPassword(Request $request, \Swift_Mailer $emailer, TokenGeneratorInterface $tokenGenerator){
        if($request->isMethod('POST')){
            $email = $request->request->get("email");
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy( ["email" => $email]);

            if($user === null){
                $this->addFlash('danger', "Email Inconnu");
                return $this->redirectToRoute('Connexion');
            }
            $token = $tokenGenerator->generateToken();


            $user->setResetToken($token);

            $url = $this->generateUrl('user_reset_password', array('token'=>$token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('sortir_eni@dev.com')
                ->setTo($user->getEmail())
                ->setBody("Vous avez oublié votre mot de passe. Voici le token pour réinitialiser votre mot de passe : " . $url,'text/html');

            $emailer->send($message);
            $em->flush();
            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('Connexion');

        }

        return $this->render("security/forgottenPassword.html.twig");
    }

    /**
     * @Route("/reinitialiser/{token}", name="user_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $encoder){
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->findOneByToken($token);

            if($user === null){
                $this->addFlash('danger', 'Token inconnu');
                return $this->redirectToRoute('main_home');
            }

            /**
             * @var User $user
             */
            $user->setResetToken(null);
            $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
            $em->flush();

            $this->addFlash('success', 'password updated');

            return $this->redirectToRoute('main_home');

        }else{
            return $this->render('security/resetPassword.html.twig', ['token' => $token]);
        }
    }


    /**
     * @Route("/{id}", name="user_show_profile", requirements={"id": "\d+"})
     */
    public function showProfile(EntityManagerInterface $em, UserRepository $ur, $id=1)
    {
        $ur = $em->getRepository(User::class);
        $user = $ur->find($id);
        $user->getSite()->getName();

        return $this->render("user/show_profile.html.twig", [
            'user' => $user,
        ]);
    }
}
