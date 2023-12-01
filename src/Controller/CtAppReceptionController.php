<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ct_app_reception")
 */
class CtAppReceptionController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_reception")
     */
    public function index(): Response
    {
        return $this->render('ct_app_reception/index.html.twig', [
            'controller_name' => 'CtAppReceptionController',
        ]);
    }
}
