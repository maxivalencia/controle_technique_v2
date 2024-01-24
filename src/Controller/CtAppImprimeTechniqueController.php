<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ct_app_imprime_technique")
 */
class CtAppImprimeTechniqueController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_imprime_technique")
     */
    public function index(): Response
    {
        return $this->render('ct_app_imprime_technique/index.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }
}
