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
        $sortie = new Sortie();
        $sortForm = $this->createForm(SortieType::class, $sortie);
        $sortForm->handleRequest($request);
        if ($sortForm->isSubmitted() && $sortForm->isValid()) {
            $sortie->setOrganisateur($this->getUser());
            $etatDeBase = $em->getRepository(Etat::class)->findBy(["name"=>"Créée"]);
            $sortie->setEtat($etatDeBase[0]);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Votre sortie est bien créée ');
            return $this->redirectToRoute('main_home');
        }

        return $this->render("sortie/creerSortie.html.twig", [
            'sortForm' => $sortForm->createView()
        ]);
    }

    /**
     * @Route("/ModifierSortie", name="modify")
     */
    public function modifySortie()
    {
        return $this->render("sortie/modifierSortie.html.twig");
    }

    /**
     * @Route("/{id}/{csrf}", name="show", requirements={"id": "\d+"})
     */
    public function showSortie(EntityManagerInterface $em, SortieRepository $sortRepo, $id, $csrf, UserRepository $ur)
    {
        $sortRepo = $em->getRepository(Sortie::class);
        $sortie = $sortRepo->find($id);
        if (!$this->isCsrfTokenValid('sortie_show_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {
            $ur = $em->getRepository(User::class);
            $usersList = $ur->findAllBySortie($sortie);
            $userSorties = $em ->getRepository(Participations::class)->findByUserId($this->getUser());

            $sortie->getLieu()->getVille()->getName();
            dump($sortie);

        }
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        return $this->render("sortie/consultSortie.html.twig", [
            'sortie' => $sortie,
            'usersList' => $usersList,
            'userSortie' => $userSorties,
        ]);
    }

    /**
     * @Route("/AnnulerSortie/{id}/{csrf}", name="cancel", requirements={"id": "\d+"})
     */
    public function cancelSortie($id, $csrf, Request $request, SortieRepository $sr, EntityManagerInterface $em)
    {
        $sortRepo = $em->getRepository(Sortie::class);
        $sortie = $sortRepo->find($id);
        $sortie->getLieu()->getVille()->getName();
        $sorties = $sr->findAll();
        $id = $request->get('id');

        if (!$this->isCsrfTokenValid('sortie_cancel_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {

            $motif = $request->get("motif");
            if($motif !== null){

                $sortie = $sr->find($id);
                $em->remove($sortie);
                $em->flush();

                $this->addFlash('success', 'Votre sortie a été annulée');
                return $this->redirectToRoute('main_home');
            }
        }
        return $this->render("sortie/annulerSortie.html.twig", [
            "sorties" => $sorties,
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

            $participation = new Participations();
            $participation->setUser($user);
            $participation->setSortie($sortie);

            $em->persist($participation);
            $em->flush();

            $this->addFlash('success', 'Votre inscription a bien été prise en compte !');
            return $this->redirectToRoute('main_home');
        }
    }


        /**
         * @Route("/RemoveSortie/{id}/{csrf}",name="remove_inscription",requirements={"id": "\d+"})
         */

        public function removeInscription ($id, $csrf, ParticipationsRepository $pr,EntityManagerInterface $em)
        {
            if (!$this->isCsrfTokenValid('sortie_remove_inscription_' . $id, $csrf)) {
                throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
            } else {
                $participation =$pr->findBy(["user"=>$this->getUser(),"sortie"=>$em->getRepository(Sortie::class)->find($id)]);
                $em->remove($participation[0]);
                $em->flush();
                $this->addFlash('success', 'Votre inscription est bien annulée ');
                return $this->redirectToRoute('main_home');

            }
        }

       
}

