<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participations;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use App\Repository\ParticipationsRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/user/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{

    /**
     * @Route("/CreerSortie", name="create")
     */
    public function createSortie(Request $request, EntityManagerInterface $em)
    {
        if (!$this->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirectToRoute('Connexion');
        } else {
            if ($this->isActive()) {
                $sortie = new Sortie();
                $sortForm = $this->createForm(SortieType::class, $sortie);
                $sortForm->handleRequest($request);
                if ($sortForm->isSubmitted() && $sortForm->isValid()) {
                    $sortie->setOrganisateur($this->getUser());

                    $privee = $request->request->get('privee');

                    if($privee)
                    {
                        $sortie->setPrivee(true);
                    }
                    else{
                        $sortie->setPrivee(false);
                    }
                    if($_POST["submitButton"]=="enregistrer"){
                        $etat = $em->getRepository(Etat::class)->findBy(["name" => "Créée"]);
                        $sortie->setEtat($etat[0]);
                    }
                    if($_POST["submitButton"]=="publier"){
                        $etat = $em->getRepository(Etat::class)->findBy(["name" => "Ouverte"]);
                        $sortie->setEtat($etat[0]);
                    }

                    $em->persist($sortie);
                    $em->flush();
                    $this->addFlash('success', 'Votre sortie est bien créée ');
                    return $this->redirectToRoute('main_home');
                }

                return $this->render("sortie/creerSortie.html.twig", [
                    'sortForm' => $sortForm->createView()
                ]);
            } else {
                return $this->render('user/desactivate.html.twig');
            }
        }
    }


    /**
     * @Route("/{id}/{csrf}", name="show", requirements={"id": "\d+"})
     */
    public function showSortie(EntityManagerInterface $em, SortieRepository $sortRepo, $id, $csrf, UserRepository $ur, VilleRepository $vr)
    {
        $sortRepo = $em->getRepository(Sortie::class);
        $sortie = $sortRepo->find($id);
        if (!$this->isCsrfTokenValid('sortie_show_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {
            $ur = $em->getRepository(User::class);
            $usersList = $ur->findAllBySortie($sortie);
            $userSorties = $em->getRepository(Participations::class)->findByUserId($this->getUser());

            $sortie->getLieu()->getVille()->getName();

            $cp = $vr->find($sortie->getLieu()->getVille()->getId())->getZip();
            $data = json_decode(file_get_contents('https://geo.api.gouv.fr/communes?codePostal='.$cp.'&fields=centre&format=json&geometry=centre'));
            dump($data);
        }

        return $this->render("sortie/consultSortie.html.twig", [
            'sortie' => $sortie,
            'usersList' => $usersList,
            'userSortie' => $userSorties,
            "data" => $data
        ]);
    }

    /**
     * @Route("/ModifierSortie/{id}/{csrf}", name="update", requirements={"id": "\d+"})
     */
    public function updateSortie(EntityManagerInterface $em, SortieRepository $sortRepo, $id, $csrf, UserRepository $ur, VilleRepository $vr, Request $request)
    {
        if (!$this->isCsrfTokenValid('sortie_update_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, vous ne pouvez pas accéder à cette page !');
        } else {
            $sortie = $sortRepo->find($id);
            $sortForm = $this->createForm(SortieType::class, $sortie);
            $sortForm->handleRequest($request);
            if ($sortForm->isSubmitted() && $sortForm->isValid()) {
                if($request->request->get('privee'))
                {
                    $sortie->setPrivee(true);
                }
                if($_POST["submitButton"]=="enregistrer"){
                    $etat = $em->getRepository(Etat::class)->findBy(["name" => "Créée"]);
                    $sortie->setEtat($etat[0]);
                }
                if($_POST["submitButton"]=="publier"){
                    $etat = $em->getRepository(Etat::class)->findBy(["name" => "Ouverte"]);
                    $sortie->setEtat($etat[0]);
                }
                $em->flush();
                return $this->redirectToRoute("main_home");
            }
        }

        return $this->render("sortie/modifierSortie.html.twig", [
            'sortieForm' => $sortForm->createView(),
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/AnnulerSortie/{id}/{csrf}", name="cancel", requirements={"id": "\d+"})
     */
    public function cancelSortie($id, $csrf, Request $request, SortieRepository $sr, EntityManagerInterface $em)
    {
        $sortie = $sr->find($id);
        $id = $request->get('id');

        if (!$this->isCsrfTokenValid('sortie_cancel_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {

            $motif = $request->request->get('motif');

            if($motif){
                $sortie = $sr->find($id);
                $sortie->setEtat($em->getRepository(Etat::class)->findOneBy(['name'=>'Annulée']));
                $sortie->setAnnulation($motif);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('success', 'Votre sortie a été annulée');
                return $this->redirectToRoute('main_home');
            }

            }

        return $this->render("sortie/annulerSortie.html.twig", [
            "sorties" => $sortie,
            "id" => $id,
            "sortie"=>$sortie
        ]);
    }

    /**
     * @Route("/InscriptionSortie/{id}/{csrf}", name="inscription", requirements={"id": "\d+"})
     */
    public function sortieRegister($id, $csrf, SortieRepository $sr, UserRepository $ur, EntityManagerInterface $em)
    {
        if (!$this->isCsrfTokenValid('sortie_inscription_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {
            $sortie = $sr->find($id);
            $user = $ur->find($this->getUser()->getId());
            $participation=$em->getRepository(Participations::class)->findOneBy(['user'=>$user , 'sortie'=>$sortie]);
            if (!$participation) {

                $participation = new Participations();
                $participation->setUser($user);
                $participation->setSortie($sortie);

                $em->persist($participation);
                $em->flush();

                $this->addFlash('success', 'Votre inscription a bien été prise en compte !');
                return $this->redirectToRoute('main_home');
            }else{
                $this->addFlash('danger', 'Vous êtes déjà inscrit à cette sortie');
                return $this->redirectToRoute('main_home');
            }

    }
    }


    /**
     * @Route("/RemoveSortie/{id}/{csrf}",name="remove_inscription",requirements={"id": "\d+"})
     */

    public function removeInscription($id, $csrf, ParticipationsRepository $pr, EntityManagerInterface $em)
    {
        if (!$this->isCsrfTokenValid('sortie_remove_inscription_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {
            $participation = $pr->findBy(["user" => $this->getUser(), "sortie" => $em->getRepository(Sortie::class)->find($id)]);
            $em->remove($participation[0]);
            $em->flush();
            $this->addFlash('success', 'Votre inscription est bien annulée ');
            return $this->redirectToRoute('main_home');

        }
    }

    /**
     * @Route("/PublierSortie/{id}/{csrf}", name="open", requirements={"id": "\d+"})
     */
    public function openSortie($id, $csrf, Request $request, SortieRepository $sr, EntityManagerInterface $em)
    {
        if (!$this->isCsrfTokenValid('sortie_open_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {
            $sortie = $em->getRepository(Sortie::class)->find($id);
            $etat = $em->getRepository(Etat::class)->findBy(["name"=>"Ouverte"]);
            $sortie->setEtat($etat[0]);
            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Votre sortie a été publiée');
        }
        return $this->redirectToRoute('main_home');
    }


    public function isActive()
    {
        if ($this->getUser()->getActive()) {
            return true;
        } else {
            return false;
        }
    }
}

