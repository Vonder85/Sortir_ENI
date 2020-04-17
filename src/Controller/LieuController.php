<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/user/lieu", name="lieux_list")
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
     * @Route("/user/lieuCoordonnees/{id}",requirements={"id"="\d+"},name="coordinate_place")
     * @Route("/user/lieuCoordonnees",name="coordinate_place_empty")
     */
    public function getLieu($id, Request $req, LieuRepository $repo){
      if ($req->isXmlHttpRequest()){

           $lieu = $repo->find($id);

           $data = [
               'name' => $lieu->getName(),
               'street' => $lieu->getStreet(),
               'zip'=>$lieu->getVille()->getZip(),
               'ville' => $lieu->getVille()->getName(),
               'longitude'=>$lieu->getLongitude(),
               'latitude'=>$lieu->getLatitude()
           ];

           return new JsonResponse($data);
      }
       return new Response("Erreur : Ce n'est pas une requête Ajax",400);
    }
}
