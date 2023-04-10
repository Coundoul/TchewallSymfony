<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessessionController extends AbstractController
{
    #[Route('/sessession', name: 'app_sessession')]
    public function index(\Symfony\Component\HttpFoundation\Request $request): Response
    {
        // session_start()
        $session = $request ->getSession();
        $nbrVisite = 0;
        if($session-> has($nbrVisite)){
            $nbrVisite= $session->get('nbrVisite')+1;

        }
        else{
            $nbrVisite = 1;
        }
        $session->set('nbrVisite', $nbrVisite);
        return $this->render('sessession/index.html.twig');
    }
}
