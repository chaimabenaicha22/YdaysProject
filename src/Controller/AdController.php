<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads= $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
      /**
     * permet de creer une annonce
     * @Route("ads/new" , name="ads_create")
     */
    public function create(Request $request)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}