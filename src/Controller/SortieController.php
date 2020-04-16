<?php

namespace App\Controller;

use App\Entity\Participations;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/CreerSortie", name="create")
     */
    public function createSortie(Request $request,EntityManagerInterface $em)
    {
        $sortie = new Sortie();
        $sortForm=$this->createForm(SortieType::class,$sortie);
        $sortForm->handleRequest($request);
        if ($sortForm->isSubmitted()&& $sortForm->isValid())
        {
            $sortie->setOrganisateur($this->getUser());
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success','Votre sortie est bien créée ');
            return $this->redirectToRoute('main_home');
        }

        return $this->render("sortie/creerSortie.html.twig",[
            'sortForm'=>$sortForm->createView()
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
    public function showSortie($id, $csrf)
    {
        if(!$this->isCsrfTokenValid('sortie_show_'.$id, $csrf)){
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        }else{

        }
        return $this->render("sortie/consultSortie.html.twig");
    }

    /**
     * @Route("/AnnulerSortie", name="cancel")
     */
    public function cancelSortie()
    {
        return $this->render("sortie/annulerSortie.html.twig");
    }

    /**
     * @Route("/InscriptionSortie/{id}/{csrf}", name="inscription", requirements={"id": "\d+"})
     */
    public function sortieRegister($id, $csrf, SortieRepository $sr, UserRepository $ur, EntityManagerInterface $em){
        if(!$this->isCsrfTokenValid('sortie_inscription_'.$id, $csrf)){
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        }else{
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
}
