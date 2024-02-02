<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ct_app_historique")
 */
class CtAppHistoriqueController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_historique")
     */
    public function index(): Response
    {
        /* return $this->redirectToRoute('app_ct_app_historique', [], Response::HTTP_SEE_OTHER); */
        return $this->render('ct_app_historique/index.html.twig', [
            'controller_name' => 'CtAppHistoriqueController',
        ]);
    }
}
