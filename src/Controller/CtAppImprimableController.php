<?php

namespace App\Controller;

use App\Repository\CtReceptionRepository;
use App\Repository\CtTypeReceptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Controller\Datetime;
use App\Entity\CtTypeReception;

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
     * @Route("/fiche_de_controle_reception", name="app_ct_app_imprimable_fiche_de_controle_reception", methods={"GET", "POST"})
     */
    public function FicheDeControleReception(Request $request, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository)//: Response
    {
        $type_reception = "";
        $date_reception = new \DateTime();
        $date_of_reception = new \DateTime();
        $type_reception_id = new CtTypeReception;
        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            //$recherche = $form['ct_type_reception_id'];
            $recherche = $rechercheform['ct_type_reception_id'];
            $date_reception = $rechercheform['date'];
            $date_of_reception = new \DateTime($date_reception);
            $type_reception_id = $ctTypeReceptionRepository->findOneBy(["id" => $recherche]);
            $type_reception = $type_reception_id->getTprcpLibelle();
        }
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        //$logo = $this->getParameter('logo').'logo_dgsr_3.png';
        //$logo_data = base64_encode(file_get_contents($logo));
        //$logo_src = 'data:image/png;base64,'.$logo_data;
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_fiche_de_controle_reception')."/".$type_reception."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }

        //$liste_receptions = $ctReceptionRepository->findBy(["ct_type_reception_id" => $type_reception_id, "ct_centre_id" => $this->getUser()->getCtCentreId(), "rcp_created" => $date_of_reception], ["id" => "DESC"]);
        //$liste_receptions = $ctReceptionRepository->findBy(["rcp_created" => $date_of_reception], ["id" => "DESC"]);
        $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $this->getUser()->getCtCentreId(), $date_of_reception);

        $html = $this->renderView('ct_app_imprimable/fiche_de_controle_reception.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'province' => $this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvNom(),
            'centre' => $this->getUser()->getCtCentreId()->getCtrNom(),
            'user' => $this->getUser(),
            'type' => $type_reception,
            'date_reception' => $date_of_reception,
            'ct_receptions' => $liste_receptions,
        ]);
        $dompdf->loadHtml($html);
        /* $dompdf->setPaper('A4', 'portrait'); */
        $dompdf->setPaper('A4', 'landscape');
        //$dompdf->set_option("isPhpEnabled", true);
        //$dompdf->setOptions("isPhpEnabled", true);
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "Fiche_De_Controle_".$type_reception."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("Fiche_De_Controle_".$type_reception."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => false,
        ]);
    }
}
