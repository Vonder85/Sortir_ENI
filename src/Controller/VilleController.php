<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/user/ville", name="ville_" )
 */
class VilleController extends AbstractController
{
    /**
     * @Route("/createVille",name="create")
     */
    public function createVille(Request $request, EntityManagerInterface $em)
    {
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid())
        {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'La ville a bien été ajoutée');
            return $this->redirectToRoute('lieu_add');
        }
        return $this->render('ville/create.html.twig', [
            'villeForm'=>$villeForm->createView()
        ]);
    }
}