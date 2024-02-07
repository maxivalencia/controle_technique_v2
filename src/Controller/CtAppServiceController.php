<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CtAppServiceController extends AbstractController
{
    #[Route('/ct/app/service', name: 'app_ct_app_service')]
    public function index(): Response
    {
        return $this->render('ct_app_service/index.html.twig', [
            'controller_name' => 'CtAppServiceController',
        ]);
    }
}
