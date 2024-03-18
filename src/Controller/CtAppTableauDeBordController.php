<?php

namespace App\Controller;

use App\Entity\CtHistorique;
use App\Entity\CtHistoriqueType;
//use App\Form\CtHistoriqueType;
use App\Form\CtHistoriqueTypeType;
use App\Repository\CtHistoriqueRepository;
use App\Repository\CtHistoriqueTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ct_app_tableau_de_bord")
 */
class CtAppTableauDeBordController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_tableau_de_bord")
     */
    public function index(): Response
    {
        /* ct_app_tableau_de_bord */
        return $this->render('ct_app_tableau_de_bord/index.html.twig', [
            'controller_name' => 'CtAppTableauDeBordController',
        ]);
    }
}
