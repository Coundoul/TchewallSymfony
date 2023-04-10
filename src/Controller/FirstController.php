<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/first', name: 'app_first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }

    #[Route('/multi/{entier1<\d+>}/{entier2<\d+>}',
        name:'multiplication',
        //requirements: ['entier1'=>'\d+', 'entier2'=>'\d+']
    )]
    public function multiplication($entier1, $entier2) : Response{
        $resultat = $entier2 * $entier1;
        return new Response(content: "<h1>$resultat</h1>");
    }
}
