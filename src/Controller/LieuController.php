<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu", name="lieux_list")
     */
    public function list(EntityManagerInterface $em)
    {
        $repo =$em ->getRepository(Lieu::class);
        $lieu = $repo ->findAll();

        return $this->render('lieu/list.html.twig', [
            'lieux' => '$lieux',
        ]);
    }

    public function add(EntityManagerInterface $em, Request $request)
    {
        $lieu = new Lieu();

        $lieuForm = $this ->createForm(LieuType::class, $lieu);
        $lieuForm -> handleRequest($request);
        if($lieuForm -> isSubmitted() && $lieuForm -> isValid())
        {
            $em -> persist($lieu);
            $em -> flush();

            $this -> addFlash('success', 'Ce lieu a bien été ajouté');

            return $this ->redirectToRoute('#');
        }
        return $this->render('lieu/add.html.twig',
                             ["lieuForm"=>$lieuForm->createView()]);

    }

    /**
     * @Route("/lieuCoordonnees/{id}",requirements={"id"="\d+"},name="coordinate_place")
     */

    public function getPlaceCoordinate($id){
        dump("test1");
        //return "Test";

        return $this->json(["test"]);
    }
}
