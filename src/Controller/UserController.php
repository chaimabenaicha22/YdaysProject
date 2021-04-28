<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{email}", name="user_show")
     */
    public function index(Utilisateur $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}