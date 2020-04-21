<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site", name="site")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/admin/site/add",name="site_add")
     */
    public function add(EntityManagerInterface $em, Request $request){
      $site = new Site();
      $siteForm =$this->createForm(SiteType::class,$site);
      $siteForm->handleRequest($request);
      if ($siteForm->isSubmitted() && $siteForm->isValid()){
          $em->persist($site);
          $em->flush();

          $this->addFlash('success', 'Le site de rattachement a bien été ajouté');
          return $this->redirectToRoute('admin_home');
      }
      return $this->render('admin/addSite.html.twig',[
          'siteForm'=>$siteForm->createView()
      ]);
    }
}
