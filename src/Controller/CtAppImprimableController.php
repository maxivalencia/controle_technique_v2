<?php

namespace App\Controller;

use App\Repository\CtMotifTarifRepository;
use App\Repository\CtReceptionRepository;
use App\Repository\CtTypeDroitPTACRepository;
use App\Repository\CtAutreRepository;
use App\Repository\CtTypeReceptionRepository;
use App\Repository\CtImprimeTechRepository;
use App\Repository\CtVisiteExtraTarifRepository;
use App\Repository\CtDroitPTACRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Controller\Datetime;
use App\Entity\CtTypeReception;
use Doctrine\Common\Collections\ArrayCollection;

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
    public function FicheDeControleReception(Request $request, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
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

        // teste date, comparaison avant utilisation rcp_num_group
        // $ctAutreRepository = new CtAutreRepository();
        $deploiement = $ctAutreRepository->findOneBy(["nom" => "DEPLOIEMENT"]);
        $dateDeploiement = $deploiement->getAttribut();
        if(new \DateTime($dateDeploiement) > $date_of_reception){
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $this->getUser()->getCtCentreId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            $liste_receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
        }
        //$liste_receptions = $ctReceptionRepository->findBy(["ct_type_reception_id" => $type_reception_id, "ct_centre_id" => $this->getUser()->getCtCentreId(), "rcp_created" => $date_of_reception], ["id" => "DESC"]);
        //$liste_receptions = $ctReceptionRepository->findBy(["rcp_created" => $date_of_reception], ["id" => "DESC"]);
        //$liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $this->getUser()->getCtCentreId(), $date_of_reception);

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
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/feuille_de_caisse_reception", name="app_ct_app_imprimable_feuille_de_caisse_reception", methods={"GET", "POST"})
     */
    public function FeuilleDeCaisseReception(Request $request, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
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

        $dossier = $this->getParameter('dossier_feuille_de_caisse_reception')."/".$type_reception."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }

        // teste date, comparaison avant utilisation rcp_num_group
        //$ctAutreRepository = new CtAutreRepository();
        $deploiement = $ctAutreRepository->findOneBy(["nom" => "DEPLOIEMENT"]);
        $dateDeploiement = $deploiement->getAttribut();
        $autreTva = $ctAutreRepository->findOneBy(["nom" => "TVA"]);
        $prixTva = $autreTva->getAttribut();
        $autreTimbre = $ctAutreRepository->findOneBy(["nom" => "TIMBRE"]);
        $prixTimbre = $autreTimbre->getAttribut();
        $timbre = floatval($prixTimbre);
        $nombreReceptions = 0;
        $totalDesDroits = 0;
        $totalDesPrixPv = 0;
        $totalDesTVA = 0;
        $totalDesTimbres = 0;
        $montantTotal = 0;
        if(new \DateTime($dateDeploiement) > $date_of_reception){
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $this->getUser()->getCtCentreId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            $liste_receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
        }
        //$liste_receptions = $ctReceptionRepository->findBy(["ct_type_reception_id" => $type_reception_id, "ct_centre_id" => $this->getUser()->getCtCentreId(), "rcp_created" => $date_of_reception], ["id" => "DESC"]);
        //$liste_receptions = $ctReceptionRepository->findBy(["rcp_created" => $date_of_reception], ["id" => "DESC"]);
        //$liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $this->getUser()->getCtCentreId(), $date_of_reception);
        $liste_des_receptions = new ArrayCollection();
        $tarif = 0;
        if($liste_receptions != null){
            foreach($liste_receptions as $liste){
                $genre = $liste->getCtGenreId();
                $motif = $liste->getCtMotifId();
                $calculable = $motif->isMtfIsCalculable();
                $tarif = 0;
                if($calculable == false){
                    $motifTarif = $ctMotifTarifRepository->findBy(["ct_motif_id" => $motif->getId()], ["ct_arrete_prix" => "DESC"]);
                    foreach($motifTarif as $mtf){
                        $arretePrix = $mtf->getCtArretePrix();
                        if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                            $tarif = $mtf->getMtfTrfPrix();
                            break;
                        }
                    }
                }
                if($calculable == true){
                    $genreCategorie = $genre->getCtGenreCategorieId();
                    $typeDroit = $ctTypeDroitPTACRepository->findOneBy(["tp_dp_libelle" => "Réception"]);
                    //$droits = $ctDroitPTACRepository->findBy(["ct_genre_categorie_id" => 1, "ct_type_droit_ptac_id" => 1], ["ct_arrete_prix_id" => "DESC"]);
                    $droits = $ctDroitPTACRepository->findBy(["ct_genre_categorie_id" => $genreCategorie->getId(), "ct_type_droit_ptac_id" => $typeDroit->getId()], ["ct_arrete_prix_id" => "DESC", "dp_prix_max" => "DESC"]);
                    foreach($droits as $dt){
                        //$tarif = $dt->getDpDroit();
                        if(($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() > ($dt->getDpPrixMin() * 1000)) && ($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() <= ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
                            $tarif = $dt->getDpDroit();
                            break;
                        }elseif($dt->getDpPrixMin() <= $dt->getDpPrixMax() && $dt->getDpPrixMin() == 0 && $dt->getDpPrixMax() == 0){
                            $tarif = $dt->getDpDroit();
                            break;
                        }
                    }
                }
                $pvId = $ctImprimeTechRepository->findOneBy(["abrev_imprime_tech" => "PVO"]);
                $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
                $prixPv = 0;
                foreach($arretePvTarif as $apt){
                    $arretePrix = $apt->getCtArretePrixId();
                    if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        $prixPv = 2 * $apt->getVetPrix();
                        break;
                    }
                }
                $droit = $tarif + $prixPv;
                $tva = ($droit * floatval($prixTva)) / 100;
                $montant = $droit + $tva + $timbre;
                $rcp = [
                    "controle_pv" => $liste->getRcpNumPv(),
                    "motif" => $motif,
                    "genre" => $genre,
                    "immatriculation" => $liste->getRcpImmatriculation(),
                    "droit" => $tarif,
                    "prix_pv" => $prixPv,
                    "tva" => $tva,
                    "timbre" => $timbre,
                    "montant" => $montant,
                    "utilisation" => $liste->getCtUtilisationId(),
                ];
                $liste_des_receptions->add($rcp);
                $nombreReceptions = $nombreReceptions + 1;
                $totalDesDroits = $totalDesDroits + $tarif;
                $totalDesPrixPv = $totalDesPrixPv + $prixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;
            }
        }

        $html = $this->renderView('ct_app_imprimable/feuille_de_caisse_reception.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'province' => $this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvNom(),
            'centre' => $this->getUser()->getCtCentreId()->getCtrNom(),
            'user' => $this->getUser(),
            'type' => $type_reception,
            'date_reception' => $date_of_reception,
            'nombre_reception' => $nombreReceptions,
            'total_des_droits' => $totalDesDroits,
            'total_des_prix_pv' => $totalDesPrixPv,
            'total_des_tva' => $totalDesTVA,
            'total_des_timbres' => $totalDesTimbres,
            'montant_total' => $montantTotal,
            'ct_receptions' => $liste_des_receptions,
        ]);
        $dompdf->loadHtml($html);
        /* $dompdf->setPaper('A4', 'portrait'); */
        $dompdf->setPaper('A4', 'landscape');
        //$dompdf->set_option("isPhpEnabled", true);
        //$dompdf->setOptions("isPhpEnabled", true);
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "Feuille_De_Caisse_".$type_reception."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("Feuille_De_Caisse_".$type_reception."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }
}
