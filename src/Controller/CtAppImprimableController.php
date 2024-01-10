<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/ct_app_imprimable")
 */
class CtAppImprimableController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_imprimable")
     */
    public function index(): Response
    {
        return $this->render('ct_app_imprimable/index.html.twig', [
            'controller_name' => 'CtAppImprimableController',
        ]);
    }

    /**
     * @Route("/fiche_de_controle_isole", name="app_ct_app_imprimable_fiche_de_controle_isole")
     */
    public function FicheDeControleIsole(): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        

        $date = new \DateTime();
        $logo = $this->getParameter('logo').'logo_dgsr.png';
        $dossier = $this->getParameter('photo').$photo;
        return $this->render('ct_app_imprimable/index.html.twig', [
            'controller_name' => 'CtAppImprimableController',
        ]);
    }
}
