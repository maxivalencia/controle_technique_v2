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
use App\Repository\CtCentreRepository;
use App\Repository\CtUtilisationRepository;
use App\Repository\CtVehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Controller\Datetime;
use App\Entity\CtCentre;
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
    public function FicheDeControleReception(Request $request, CtCentreRepository $ctCentreRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $type_reception = "";
        $date_reception = new \DateTime();
        $date_of_reception = new \DateTime();
        $type_reception_id = new CtTypeReception();
        $centre = new CtCentre();
        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            //$recherche = $form['ct_type_reception_id'];
            $recherche = $rechercheform['ct_type_reception_id'];
            $date_reception = $rechercheform['date'];
            $date_of_reception = new \DateTime($date_reception);
            $type_reception_id = $ctTypeReceptionRepository->findOneBy(["id" => $recherche]);
            $type_reception = $type_reception_id->getTprcpLibelle();
            if($rechercheform['ct_centre_id'] != ""){
                $centre = $ctCentreRepository->findOneBy(["id" => $rechercheform['ct_centre_id']]);
            } else{
                $centre = $this->getUser()->getCtCentreId();
            }
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

        $dossier = $this->getParameter('dossier_fiche_de_controle_reception')."/".$type_reception."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }

        // teste date, comparaison avant utilisation rcp_num_group
        // $ctAutreRepository = new CtAutreRepository();
        $deploiement = $ctAutreRepository->findOneBy(["nom" => "DEPLOIEMENT"]);
        $dateDeploiement = $deploiement->getAttribut();
        if(new \DateTime($dateDeploiement) > $date_of_reception){
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            $liste_receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
        }
        //$liste_receptions = $ctReceptionRepository->findBy(["ct_type_reception_id" => $type_reception_id, "ct_centre_id" => $this->getUser()->getCtCentreId(), "rcp_created" => $date_of_reception], ["id" => "DESC"]);
        //$liste_receptions = $ctReceptionRepository->findBy(["rcp_created" => $date_of_reception], ["id" => "DESC"]);
        //$liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $this->getUser()->getCtCentreId(), $date_of_reception);

        $html = $this->renderView('ct_app_imprimable/fiche_de_controle_reception.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'province' => $centre->getCtProvinceId()->getPrvNom(),
            'centre' => $centre->getCtrNom(),
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
        $filename = "Fiche_De_Controle_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("Fiche_De_Controle_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/feuille_de_caisse_reception", name="app_ct_app_imprimable_feuille_de_caisse_reception", methods={"GET", "POST"})
     */
    public function FeuilleDeCaisseReception(Request $request, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $type_reception = "";
        $date_reception = new \DateTime();
        $date_of_reception = new \DateTime();
        $type_reception_id = new CtTypeReception();
        $centre = new CtCentre();
        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            //$recherche = $form['ct_type_reception_id'];
            $recherche = $rechercheform['ct_type_reception_id'];
            $date_reception = $rechercheform['date'];
            $date_of_reception = new \DateTime($date_reception);
            $type_reception_id = $ctTypeReceptionRepository->findOneBy(["id" => $recherche]);
            $type_reception = $type_reception_id->getTprcpLibelle();
            if($rechercheform['ct_centre_id'] != ""){
                $centre = $ctCentreRepository->findOneBy(["id" => $rechercheform['ct_centre_id']]);
            } else{
                $centre = $this->getUser()->getCtCentreId();
            }
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

        $dossier = $this->getParameter('dossier_feuille_de_caisse_reception')."/".$type_reception."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
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
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
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
                $prixPv = 0;
                $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
                $utilisation = $liste->getCtUtilisationId();
                if($utilisation != $utilisationAdministratif){
                    if($calculable == false){
                        $motifTarif = $ctMotifTarifRepository->findBy(["ct_motif_id" => $motif->getId()], ["ct_arrete_prix" => "DESC"]);
                        foreach($motifTarif as $mtf){
                            $arretePrix = $mtf->getCtArretePrix();
                            //if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                            if($liste->getRcpCreated() >= $arretePrix->getArtDateApplication()){
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
                    foreach($arretePvTarif as $apt){
                        $arretePrix = $apt->getCtArretePrixId();
                        //if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        if($liste->getRcpCreated() >= $arretePrix->getArtDateApplication()){
                            $prixPv = 2 * $apt->getVetPrix();
                            break;
                        }
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
                    "utilisation" => $utilisation,
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
            'province' => $centre->getCtProvinceId()->getPrvNom(),
            'centre' => $centre->getCtrNom(),
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
        $filename = "Feuille_De_Caisse_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("Feuille_De_Caisse_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_reception_isole/{id}", name="app_ct_app_imprimable_proces_verbal_reception_isole", methods={"GET", "POST"})
     */
    public function ProcesVerbalReceptionIsole(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $identification = intval($id);
        $reception = $ctReceptionRepository->findOneBy(["id" => $identification], ["id" => "DESC"]);
        $vehicule = $ctVehiculeRepository->findOneBy(["id" => $reception->getId()], ["id" => "DESC"]);
        $type_reception_id = $ctTypeReceptionRepository->findOneBy(["id" => $reception->getCtTypeReceptionId()]);
        $type_reception = $type_reception_id->getTprcpLibelle();
        $centre = $ctCentreRepository->findOneBy(["id" => $reception->getCtCentreId()]);
        
        $reception_data = ["id" => $identification,
            "ct_genre_id" => $vehicule->getCtGenreId()->getGrLibelle(),
            "ct_marque_id" => $vehicule->getCtMarqueId()->getMrqLibelle(),
            "vhc_type" => $vehicule->getVhcType(),
            "vhc_num_serie" => $vehicule->getVhcNumSerie(),
            "vhc_num_moteur" => $vehicule->getVhcNumMoteur(),
            "ct_carrosserie_id" => $reception->getCtCarrosserieId()->getCrsLibelle(),
            "ct_source_energie_id" => $reception->getCtSourceEnergieId()->getSreLibelle(),
            "vhc_cylindre" => $vehicule->getVhcCylindre(),
            "vhc_puissance" => $vehicule->getVhcPuissance(),
            "vhc_poids_vide" => $vehicule->getVhcPoidsVide(),
            "vhc_charge_utile" => $vehicule->getVhcChargeUtile(),
            "vhc_poids_total_charge" => $vehicule->getVhcPoidsTotalCharge(),
            "ct_utilisation_id" => $reception->getCtUtilisationId()->getUtLibelle(),
            "ct_motif_id" => $reception->getCtMotifId()->getMtfLibelle(),
            "rcp_immatriculation" => $reception->getRcpImmatriculation(),
            "rcp_proprietaire" => $reception->getRcpProprietaire(),
            "rcp_profession" => $reception->getRcpProfession(),
            "rcp_adresse" => $reception->getRcpAdresse(),
            "rcp_nbr_assis" => $reception->getRcpNbrAssis(),
            "rcp_ngr_debout" => $reception->getRcpNgrDebout(),
            "rcp_mise_service" => $reception->getRcpMiseService(),
            "ct_verificateur_id" => $reception->getCtVerificateurId()->getUsrNom(),
            "ct_type_reception_id" => $type_reception,
            "ct_centre_id" => $centre->getCtrNom(),
            "ct_province_id" => $centre->getCtProvinceId()->getPrvNom(),
            "rcp_num_pv" => $reception->getRcpNumPv(),
            "rcp_created" => $reception->getRcpCreated(),
        ];

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

        $dossier = $this->getParameter('dossier_reception_isole')."/".$type_reception."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
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
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
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
                $prixPv = 0;
                $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
                $utilisation = $liste->getCtUtilisationId();
                if($utilisation != $utilisationAdministratif){
                    if($calculable == false){
                        $motifTarif = $ctMotifTarifRepository->findBy(["ct_motif_id" => $motif->getId()], ["ct_arrete_prix" => "DESC"]);
                        foreach($motifTarif as $mtf){
                            $arretePrix = $mtf->getCtArretePrix();
                            //if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                            if($liste->getRcpCreated() >= $arretePrix->getArtDateApplication()){
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
                    foreach($arretePvTarif as $apt){
                        $arretePrix = $apt->getCtArretePrixId();
                        //if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        if($liste->getRcpCreated() >= $arretePrix->getArtDateApplication()){
                            $prixPv = 2 * $apt->getVetPrix();
                            break;
                        }
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
                    "utilisation" => $utilisation,
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

        $html = $this->renderView('ct_app_imprimable/proces_verbal_reception_isole.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'province' => $centre->getCtProvinceId()->getPrvNom(),
            'centre' => $centre->getCtrNom(),
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
        $dompdf->setPaper('A4', 'portrait');
        /* $dompdf->setPaper('A4', 'landscape'); */
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "Feuille_De_Caisse_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("Feuille_De_Caisse_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }
}
