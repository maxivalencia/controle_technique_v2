<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CtAppSatistiqueController extends AbstractController
{
    #[Route('/ct/app/satistique', name: 'app_ct_app_satistique')]
    public function index(): Response
    {
        return $this->render('ct_app_satistique/index.html.twig', [
            'controller_name' => 'CtAppSatistiqueController',
        ]);
    }
}
