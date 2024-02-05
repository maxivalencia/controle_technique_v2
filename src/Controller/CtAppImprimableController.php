<?php

namespace App\Controller;

use App\Repository\CtMotifTarifRepository;
use App\Repository\CtReceptionRepository;
use App\Repository\CtConstAvDedRepository;
use App\Repository\CtConstAvDedCaracRepository;
use App\Repository\CtConstAvDedTypeRepository;
use App\Repository\CtVisiteRepository;
use App\Repository\CtVisiteExtraRepository;
use App\Repository\CtVisiteExtraTarifRepository;
use App\Repository\CtTypeDroitPTACRepository;
use App\Repository\CtAutreRepository;
use App\Repository\CtTypeReceptionRepository;
use App\Repository\CtImprimeTechRepository;
use App\Repository\CtDroitPTACRepository;
use App\Repository\CtCentreRepository;
use App\Repository\CtUtilisationRepository;
use App\Repository\CtVehiculeRepository;
use App\Repository\CtTypeVisiteRepository;
use App\Repository\CtUsageTarifRepository;
use App\Repository\CtUsageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Controller\Datetime;
use App\Entity\CtCentre;
use App\Entity\CtTypeReception;
use App\Entity\CtConstAvDedCarac;
use App\Entity\CtVisite;
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
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_fiche_de_controle_reception')."/".$type_reception."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
        $deploiement = $ctAutreRepository->findOneBy(["nom" => "DEPLOIEMENT"]);
        $dateDeploiement = $deploiement->getAttribut();
        if(new \DateTime($dateDeploiement) > $date_of_reception){
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            $liste_receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
        }
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
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "FICHE_DE_CONTROLE_RECEP".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("FICHE_DE_CONTROLE_RECEP".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
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
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_feuille_de_caisse_reception')."/".$type_reception."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
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
                            if($liste->getRcpCreated() >= $arretePrix->getArtDateApplication()){
                                $tarif = $mtf->getMtfTrfPrix();
                                break;
                            }
                        }
                    }
                    if($calculable == true){
                        $genreCategorie = $genre->getCtGenreCategorieId();
                        $typeDroit = $ctTypeDroitPTACRepository->findOneBy(["tp_dp_libelle" => "Réception"]);
                        $droits = $ctDroitPTACRepository->findBy(["ct_genre_categorie_id" => $genreCategorie->getId(), "ct_type_droit_ptac_id" => $typeDroit->getId()], ["ct_arrete_prix_id" => "DESC", "dp_prix_max" => "DESC"]);
                        foreach($droits as $dt){
                            if(($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() >= ($dt->getDpPrixMin() * 1000)) && ($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() < ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
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
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "FEUILLE_DE_CAISSE_RECEP_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("FEUILLE_DE_CAISSE_RECEP_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/feuille_de_caisse_constatation", name="app_ct_app_imprimable_feuille_de_caisse_constatation", methods={"GET", "POST"})
     */
    public function FeuilleDeCaisseConstatation(Request $request, CtConstAvDedCaracRepository $ctConstAvDedCaracRepository, CtConstAvDedRepository $ctConstAvDedRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $type_reception = "";
        $date_constatation = new \DateTime();
        $date_of_constatation = new \DateTime();
        $centre = new CtCentre();
        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            $date_constatation = $rechercheform['date'];
            $date_of_constatation = new \DateTime($date_constatation);
            if($rechercheform['ct_centre_id'] != ""){
                $centre = $ctCentreRepository->findOneBy(["id" => $rechercheform['ct_centre_id']]);
            } else {
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
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_feuille_de_caisse_constatation')."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
        $deploiement = $ctAutreRepository->findOneBy(["nom" => "DEPLOIEMENT"]);
        $dateDeploiement = $deploiement->getAttribut();
        $autreTva = $ctAutreRepository->findOneBy(["nom" => "TVA"]);
        $prixTva = $autreTva->getAttribut();
        $autreTimbre = $ctAutreRepository->findOneBy(["nom" => "TIMBRE"]);
        $prixTimbre = $autreTimbre->getAttribut();
        $timbre = floatval($prixTimbre);
        $nombreConstatations = 0;
        $totalDesDroits = 0;
        $totalDesPrixPv = 0;
        $totalDesTVA = 0;
        $totalDesTimbres = 0;
        $montantTotal = 0;
        $liste_constatations = $ctConstAvDedRepository->findByFicheDeControle($centre->getId(), $date_of_constatation);
        $liste_des_constatations = new ArrayCollection();
        $tarif = 0;
        if($liste_constatations != null){
            foreach($liste_constatations as $liste){
                $marques = $liste->getCtConstAvDedCarac();
                $carac = new CtConstAvDedCarac();
                foreach($marques as $mrq){
                    $marque = $mrq->getCtMarqueId();
                    $genre = $mrq->getCtGenreId();
                    $carac = $mrq;
                }
                $tarif = 0;
                $prixPv = 0;
                //$utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
                $genreCategorie = $genre->getCtGenreCategorieId();
                $typeDroit = $ctTypeDroitPTACRepository->findOneBy(["tp_dp_libelle" => "Constatation"]);
                $droits = $ctDroitPTACRepository->findBy(["ct_genre_categorie_id" => $genreCategorie->getId(), "ct_type_droit_ptac_id" => $typeDroit->getId()], ["ct_arrete_prix_id" => "DESC", "dp_prix_max" => "DESC"]);
                foreach($droits as $dt){
                    if(($carac->getCadPoidsTotalCharge() >= ($dt->getDpPrixMin() * 1000)) && ($carac->getCadPoidsTotalCharge() < ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
                        $tarif = $dt->getDpDroit();
                        break;
                    }elseif($dt->getDpPrixMin() <= $dt->getDpPrixMax() && $dt->getDpPrixMin() == 0 && $dt->getDpPrixMax() == 0){
                        $tarif = $dt->getDpDroit();
                        break;
                    }
                }
                $pvId = $ctImprimeTechRepository->findOneBy(["abrev_imprime_tech" => "PVO"]);
                $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
                foreach($arretePvTarif as $apt){
                    $arretePrix = $apt->getCtArretePrixId();
                    if($liste->getCadCreated() >= $arretePrix->getArtDateApplication()){
                        $prixPv = $apt->getVetPrix();
                        break;
                    }
                }
                $droit = $tarif + $prixPv;
                $tva = ($droit * floatval($prixTva)) / 100;
                $montant = $droit + $tva + $timbre;
                $cad = [
                    "controle_pv" => $liste->getCadNumero(),
                    "proprietaire" => $liste->getCadProprietaireNom(),
                    "marque" => $marque->getMrqLibelle(),
                    "genre" => $genre->getGrLibelle(),
                    "ptac" => $carac->getCadPoidsTotalCharge(),
                    "droit" => $tarif,
                    "prix_pv" => $prixPv,
                    "tht" => $droit,
                    "tva" => $tva,
                    "timbre" => $timbre,
                    "montant" => $montant,
                ];
                $liste_des_constatations->add($cad);
                $nombreConstatations = $nombreConstatations + 1;
                $totalDesDroits = $totalDesDroits + $tarif;
                $totalDesPrixPv = $totalDesPrixPv + $prixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;
            }
        }

        $html = $this->renderView('ct_app_imprimable/feuille_de_caisse_constatation.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'province' => $centre->getCtProvinceId()->getPrvNom(),
            'centre' => $centre->getCtrNom(),
            'user' => $this->getUser(),
            'type' => $type_reception,
            'date_constatation' => $date_of_constatation,
            'nombre_constatation' => $nombreConstatations,
            'total_des_droits' => $totalDesDroits,
            'total_des_prix_pv' => $totalDesPrixPv,
            'total_des_tva' => $totalDesTVA,
            'total_des_timbres' => $totalDesTimbres,
            'montant_total' => $montantTotal,
            'ct_constatations' => $liste_des_constatations,
        ]);
        $dompdf->loadHtml($html);
        /* $dompdf->setPaper('A4', 'portrait'); */
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "FEUILLE_DE_CAISSE_CONST_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("FEUILLE_DE_CAISSE_CONST_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/feuille_de_caisse_visite", name="app_ct_app_imprimable_feuille_de_caisse_visite", methods={"GET", "POST"})
     */
    public function FeuilleDeCaisseVisite(Request $request, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $type_visite = "";
        $date_visite = new \DateTime();
        $date_of_visite = new \DateTime();
        $type_visite_id = new CtTypeReception();
        $centre = new CtCentre();

        $liste_usage = $ctUsageRepository->findAll();
        $liste_des_usages = new ArrayCollection();
        //$array_usages = array();
        //$array_usages = [];
        foreach($liste_usage as $lstu){
            $usg = [
                "usage" => $lstu->getUsgLibelle(),
                "nombre" => 0,
            ];
            //array_push()
            $liste_des_usages->add($usg);
            //$array_usages[$lstu->getUsgLibelle()] = 0;
        }

        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            $recherche = $rechercheform['ct_type_visite_id'];
            $date_visite = $rechercheform['date'];
            $date_of_visite = new \DateTime($date_visite);
            $type_visite_id = $ctTypeVisiteRepository->findOneBy(["id" => $recherche]);
            $type_visite = $type_visite_id->getTpvLibelle();
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
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_feuille_de_caisse_visite')."/".$type_visite."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
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
        $totalDesPrixCartes = 0;
        $totalDesPrixCarnets = 0;
        $montantTotal = 0;
        if(new \DateTime($dateDeploiement) > $date_of_visite){
            $liste_visites = $ctVisiteRepository->findByFicheDeControle($type_visite_id->getId(), $centre->getId(), $date_of_visite);
        }else{
            $nomGroup = $date_of_visite->format('d').'/'.$date_of_visite->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$type_visite.'/'.$date_of_visite->format("Y");
            $liste_visites = $ctVisiteRepository->findBy(["vst_num_feuille_caisse" => $nomGroup, "vst_is_active" => true]);
        }
        $liste_des_visites = new ArrayCollection();
        $tarif = 0;
        if($liste_visites != null){
            foreach($liste_visites as $liste){
                if($liste->isVstIsContreVisite() == true){
                    continue;
                }
                $usage = $liste->getCtUsageId();
                //$motif = $liste->getCtMotifId();
                //$calculable = $motif->isMtfIsCalculable();
                $tarif = 0;
                $prixPv = 0;
                $carnet = 0;
                $carte = 0;
                $aptitude = "Inapte";
                //$listes_cartes = $ctVisiteExtraRepository->findOneBy(["" => $liste->getId()]);
                $listes_autre = $liste->getVstExtra();
                $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
                $utilisation = $liste->getCtUtilisationId();
                if($utilisation != $utilisationAdministratif){
                    $usage_tarif = $ctUsageTarifRepository->findOneBy(["ct_usage_id" => $usage->getId(), "ct_type_visite_id" => $type_visite_id], ["usg_trf_annee" => "DESC"]);
                    $tarif = $usage_tarif->getUsgTrfPrix();
                    $pvId = $ctImprimeTechRepository->findOneBy(["abrev_imprime_tech" => "PVO"]);
                    $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
                    foreach($arretePvTarif as $apt){
                        $arretePrix = $apt->getCtArretePrixId();
                        //if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        if($liste->isVstIsContreVisite() == false){
                            if($liste->getVstCreated() >= $arretePrix->getArtDateApplication()){
                                if($liste->isVstIsApte()){
                                    $prixPv = $apt->getVetPrix();
                                    $aptitude = "Apte";
                                } else {
                                    $prixPv = 2 * $apt->getVetPrix();
                                    $aptitude = "Inapte";
                                }
                                break;
                            }
                        } else {
                            continue;
                        }
                    }
                }
                foreach($listes_autre as $autre){
                    $vet = $ctVisiteExtraTarifRepository->findOneBy(["ct_imprime_tech_id" => $autre->getId()], ["vet_annee" => "DESC"]);
                    if($autre->getId() == 1){
                        $carnet = $carnet + $vet->getVetPrix();
                    } else {
                        $carte = $carte + $vet->getVetPrix();
                    }
                }
                $compteur_usage = 0;
                foreach($liste_des_usages as $ldu){
                //$array_usages[$liste->getCtUsageId()->getUsgLibelle()]++; 
                //foreach($array_usages as $au){
                    if($liste_des_usages[$compteur_usage]["usage"] == $liste->getCtUsageId()->getUsgLibelle()){
                    //if($ldu->getUsage() == $liste->getCtUsageId()->getUsgLibelle()){
                        //$ldu->getUsage();
                        /* $usg = [
                            "usage" => $lstu->getUsgLibelle(),
                            "nombre" => $liste_des_usages[$compteur_usage]["nombre"] + 1,
                        ];
                        $liste_des_usages->add($usg); */
                        //$au["nombre"]++;
                        //unset($liste_des_usages[$compteur_usage]["nombre"]);
                        $ldu["nombre"]++;
                        //break;
                        //$ldu->setNombre($ldu->setNombre() + 1);
                    }
                    $compteur_usage++;
                }
                //$array_usages[$liste->getCtUsageId()->getUsgLibelle()] += 1;

                $droit = $tarif + $prixPv + $carnet + $carte;
                $tva = ($droit * floatval($prixTva)) / 100;
                $montant = $droit + $tva + $timbre;
                $vst = [
                    "controle_pv" => $liste->getVstNumPv(),
                    "immatriculation" => $liste->getCtCarteGriseId()->getCgImmatriculation(),
                    "usage" => $liste->getCtUsageId()->getUsgLibelle(),
                    "aptitude" => $aptitude,
                    "verificateur" => $liste->getCtVerificateurId()->getUsrNom(),
                    "cooperative" => $liste->getCtCarteGriseId()->getCgNomCooperative(),
                    "droit" => $tarif,
                    "prix_pv" => $prixPv,
                    "carnet" => $carnet,
                    "carte" => $carte,
                    "tva" => $tva,
                    "timbre" => $timbre,
                    "montant" => $montant,
                    "utilisation" => $utilisation,
                ];
                $liste_des_visites->add($vst);
                $nombreReceptions = $nombreReceptions + 1;
                $totalDesDroits = $totalDesDroits + $tarif;
                $totalDesPrixPv = $totalDesPrixPv + $prixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;
                $totalDesPrixCartes = $totalDesPrixCartes + $carte;
                $totalDesPrixCarnets = $totalDesPrixCarnets + $carnet;
            }
        }

        $html = $this->renderView('ct_app_imprimable/feuille_de_caisse_visite.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'province' => $centre->getCtProvinceId()->getPrvNom(),
            'centre' => $centre->getCtrNom(),
            'user' => $this->getUser(),
            'type' => $type_visite,
            'date_visite' => $date_of_visite,
            'nombre_visite' => $nombreReceptions,
            'total_des_droits' => $totalDesDroits,
            'total_des_prix_pv' => $totalDesPrixPv,
            'total_des_tva' => $totalDesTVA,
            'total_des_timbres' => $totalDesTimbres,
            'total_des_carnets' => $totalDesPrixCarnets,
            'total_des_cartes' => $totalDesPrixCartes,
            'montant_total' => $montantTotal,
            'ct_visites' => $liste_des_visites,
            //'liste_usage' => $array_usages,
            'liste_usage' => $liste_des_usages,
        ]);
        $dompdf->loadHtml($html);
        /* $dompdf->setPaper('A4', 'portrait'); */
        $dompdf->setPaper('A4', 'landscape');
        //$dompdf->set_option("isPhpEnabled", true);
        //$dompdf->setOptions("isPhpEnabled", true);
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "FEUILLE_DE_CAISSE_VISITE_".$type_visite."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("FEUILLE_DE_CAISSE_VISITE_".$type_visite."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
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
        $date_of_reception = $reception->getRcpCreated();

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
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_reception_isole')."/".$type_reception."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
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
        /* if(new \DateTime($dateDeploiement) > $date_of_reception){
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            $liste_receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
        } */
        $liste_des_receptions = new ArrayCollection();
        $tarif = 0;
        $liste = $reception;
        //if($liste_receptions != null){
            //foreach($liste_receptions as $liste){
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
                            if(($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() >= ($dt->getDpPrixMin() * 1000)) && ($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() < ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
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
                $totalDesTHT = $totalDesDroits + $totalDesPrixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;
            //}
        //}

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
            'total_des_tht' => $totalDesTHT,
            'total_des_tva' => $totalDesTVA,
            'total_des_timbres' => $totalDesTimbres,
            'montant_total' => $montantTotal,
            //'ct_receptions' => $liste_des_receptions,
            'reception' => $reception_data,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        /* $dompdf->setPaper('A4', 'landscape'); */
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "PROCES_VERBAL_".$id."_RECEP_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("PROCES_VERBAL_".$id."_RECEP_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_constatation/{id}", name="app_ct_app_imprimable_proces_verbal_constatation", methods={"GET", "POST"})
     */
    public function ProcesVerbalConstatation(Request $request, int $id, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository, CtConstAvDedCaracRepository $ctConstAvDedCaracRepository, CtConstAvDedRepository $ctConstAvDedRepository, CtVehiculeRepository $ctVehiculeRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $identification = intval($id);
        $constatation = $ctConstAvDedRepository->findOneBy(["id" => $identification], ["id" => "DESC"]);
        //$constatation_types_carte_grise = $ctConstAvDedTypeRepository->findOne();
        $constatation_caracteristiques = $constatation->getCtConstAvDedCarac();
        foreach($constatation_caracteristiques as $constatation_carac){
            if($constatation_carac->getCtConstAvDedTypeId()->getId() == 1){
                $constatation_caracteristique_carte_grise = $constatation_carac;
            }
            if($constatation_carac->getCtConstAvDedTypeId()->getId() == 2){
                $constatation_caracteristique_corps_du_vehicule = $constatation_carac;
            }
            if($constatation_carac->getCtConstAvDedTypeId()->getId() == 3){
                $constatation_caracteristique_note_descriptive = $constatation_carac;
            }
        }
        //$constatation_caracteristique_carte_grise = $ctConstAvDedCaracRepository->findOneBy(["ct_const_av_ded_type_id" => 1, "ctConstAvDeds" => [$constatation->getId()]], ["id" => "DESC"]);
        //$constatation_caracteristique_corps_du_vehicule = $ctConstAvDedCaracRepository->findOneBy(["ct_const_av_ded_type_id" => 2, "ctConstAvDeds" => $constatation], ["id" => "DESC"]);
        //$constatation_caracteristique_note_descriptive = $ctConstAvDedCaracRepository->findOneBy(["ct_const_av_ded_type_id" => 3, "ctConstAvDeds" => $constatation], ["id" => "DESC"]);

        //$type_constatation_id = $ctTypeReceptionRepository->findOneBy(["id" => $reception->getCtTypeReceptionId()]);
        //$type_constatation = $type_reception_id->getTprcpLibelle();
        //$centre = $ctCentreRepository->findOneBy(["id" => $reception->getCtCentreId()]);
        //$date_of_reception = $reception->getRcpCreated();
        //var_dump($constatation_caracteristique_carte_grise);
        if($constatation_caracteristique_carte_grise != null){
            $constatation_carte_grise_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_carte_grise->getCadPremiereCircule(),
                "genre" => $constatation_caracteristique_carte_grise->getCtGenreId()->getGrLibelle(),
                "marque" => $constatation_caracteristique_carte_grise->getCtMarqueId()->getMrqLibelle(),
                "type" => $constatation_caracteristique_carte_grise->getCadTypeCar(),
                "numero_de_serie" => $constatation_caracteristique_carte_grise->getCadNumSerieType(),
                "numero_moteur" => $constatation_caracteristique_carte_grise->getCadNumMoteur(),
                "source_energie" => $constatation_caracteristique_carte_grise->getCtSourceEnergieId()->getSreLibelle(),
                "cylindre" => $constatation_caracteristique_carte_grise->getCadCylindre(),
                "puissance" => $constatation_caracteristique_carte_grise->getCadPuissance(),
                "carrosserie" => $constatation_caracteristique_carte_grise->getCtCarrosserieId()->getCrsLibelle(),
                "nbr_assise" => $constatation_caracteristique_carte_grise->getCadNbrAssis(),
                "charge_utile" =>$constatation_caracteristique_carte_grise->getCadChargeUtile(),
                "poids_a_vide" => $constatation_caracteristique_carte_grise->getCadPoidsVide(),
                "poids_total_a_charge" => $constatation_caracteristique_carte_grise->getCadPoidsTotalCharge(),
                "longueur" => $constatation_caracteristique_carte_grise->getCadLongueur(),
                "largeur" => $constatation_caracteristique_carte_grise->getCadLargeur(),
                "hauteur" => $constatation_caracteristique_carte_grise->getCadHauteur(),
            ];
        } else {
            $constatation_carte_grise_data = [
                "date_premiere_mise_en_circulation" => "",
                "genre" => "",
                "marque" => "",
                "type" => "",
                "numero_de_serie" => "",
                "numero_moteur" => "",
                "source_energie" => "",
                "cylindre" => "",
                "puissance" => "",
                "carrosserie" => "",
                "nbr_assise" => "",
                "charge_utile" => "",
                "poids_a_vide" => "",
                "poids_total_a_charge" => "",
                "longueur" => "",
                "largeur" => "",
                "hauteur" => "",
            ];
        }
        //return $this->redirectToRoute('app_ct_app_historique', [], Response::HTTP_SEE_OTHER);
        if($constatation_caracteristique_carte_grise != null){
            $constatation_corps_du_vehicule_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_corps_du_vehicule->getCadPremiereCircule(),
                "genre" => $constatation_caracteristique_corps_du_vehicule->getCtGenreId()->getGrLibelle(),
                "marque" => $constatation_caracteristique_corps_du_vehicule->getCtMarqueId()->getMrqLibelle(),
                "type" => $constatation_caracteristique_corps_du_vehicule->getCadTypeCar(),
                "numero_de_serie" => $constatation_caracteristique_corps_du_vehicule->getCadNumSerieType(),
                "numero_moteur" => $constatation_caracteristique_corps_du_vehicule->getCadNumMoteur(),
                "source_energie" => $constatation_caracteristique_corps_du_vehicule->getCtSourceEnergieId()->getSreLibelle(),
                "cylindre" => $constatation_caracteristique_corps_du_vehicule->getCadCylindre(),
                "puissance" => $constatation_caracteristique_corps_du_vehicule->getCadPuissance(),
                "carrosserie" => $constatation_caracteristique_corps_du_vehicule->getCtCarrosserieId()->getCrsLibelle(),
                "nbr_assise" => $constatation_caracteristique_corps_du_vehicule->getCadNbrAssis(),
                "charge_utile" =>$constatation_caracteristique_corps_du_vehicule->getCadChargeUtile(),
                "poids_a_vide" => $constatation_caracteristique_corps_du_vehicule->getCadPoidsVide(),
                "poids_total_a_charge" => $constatation_caracteristique_corps_du_vehicule->getCadPoidsTotalCharge(),
                "longueur" => $constatation_caracteristique_corps_du_vehicule->getCadLongueur(),
                "largeur" => $constatation_caracteristique_corps_du_vehicule->getCadLargeur(),
                "hauteur" => $constatation_caracteristique_corps_du_vehicule->getCadHauteur(),
            ];
        } else {
            $constatation_corps_du_vehicule_data = [
                "date_premiere_mise_en_circulation" => "",
                "genre" => "",
                "marque" => "",
                "type" => "",
                "numero_de_serie" => "",
                "numero_moteur" => "",
                "source_energie" => "",
                "cylindre" => "",
                "puissance" => "",
                "carrosserie" => "",
                "nbr_assise" => "",
                "charge_utile" => "",
                "poids_a_vide" => "",
                "poids_total_a_charge" => "",
                "longueur" => "",
                "largeur" => "",
                "hauteur" => "",
            ];
        }

        if($constatation_caracteristique_carte_grise != null){
            $constatation_note_descriptive_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_note_descriptive->getCadPremiereCircule(),
                "genre" => $constatation_caracteristique_note_descriptive->getCtGenreId()->getGrLibelle(),
                "marque" => $constatation_caracteristique_note_descriptive->getCtMarqueId()->getMrqLibelle(),
                "type" => $constatation_caracteristique_note_descriptive->getCadTypeCar(),
                "numero_de_serie" => $constatation_caracteristique_note_descriptive->getCadNumSerieType(),
                "numero_moteur" => $constatation_caracteristique_note_descriptive->getCadNumMoteur(),
                "source_energie" => $constatation_caracteristique_note_descriptive->getCtSourceEnergieId()->getSreLibelle(),
                "cylindre" => $constatation_caracteristique_note_descriptive->getCadCylindre(),
                "puissance" => $constatation_caracteristique_note_descriptive->getCadPuissance(),
                "carrosserie" => $constatation_caracteristique_note_descriptive->getCtCarrosserieId()->getCrsLibelle(),
                "nbr_assise" => $constatation_caracteristique_note_descriptive->getCadNbrAssis(),
                "charge_utile" =>$constatation_caracteristique_note_descriptive->getCadChargeUtile(),
                "poids_a_vide" => $constatation_caracteristique_note_descriptive->getCadPoidsVide(),
                "poids_total_a_charge" => $constatation_caracteristique_note_descriptive->getCadPoidsTotalCharge(),
                "longueur" => $constatation_caracteristique_note_descriptive->getCadLongueur(),
                "largeur" => $constatation_caracteristique_note_descriptive->getCadLargeur(),
                "hauteur" => $constatation_caracteristique_note_descriptive->getCadHauteur(),
            ];
        } else {
            $constatation_note_descriptive_data = [
                "date_premiere_mise_en_circulation" => "",
                "genre" => "",
                "marque" => "",
                "type" => "",
                "numero_de_serie" => "",
                "numero_moteur" => "",
                "source_energie" => "",
                "cylindre" => "",
                "puissance" => "",
                "carrosserie" => "",
                "nbr_assise" => "",
                "charge_utile" => "",
                "poids_a_vide" => "",
                "poids_total_a_charge" => "",
                "longueur" => "",
                "largeur" => "",
                "hauteur" => "",
            ];
        }

        $constatation_data = [
            "id" => $constatation->getId(),
            "centre" => $constatation->getCtCentreId()->getCtrNom(),
            "province" => $constatation->getCtCentreId()->getCtProvinceId()->getPrvNom(),
            "pv" => $constatation->getCadNumero(),
            "date" => $constatation->getCadCreated(),
            "verificateur" => $constatation->getCtVerificateurId(),
            "immatriculation" => $constatation->getCadImmatriculation(),
            "provenance" => $constatation->getCadProvenance(),
            "date_embarquement" => $constatation->getCadDateEmbarquement(),
            "port_embarquement" => $constatation->getCadLieuEmbarquement(),
            "observation" => $constatation->getCadObservation(),
            "proprietaire" => $constatation->getCadProprietaireNom(),
            "adresse" => $constatation->getCadProprietaireAdresse(),
            "conforme" => $constatation->isCadConforme() ? "CONFORME" : "NON CONFORME",
            "etat" => $constatation->isCadBonEtat() ? "OUI" : "NON",
            "securite_personne" => $constatation->isCadSecPers() ? "OUI" : "NON",
            "securite_marchandise" => $constatation->isCadSecMarch() ? "OUI" : "NON",
            "protection_environnement" => $constatation->isCadProtecEnv() ? "OUI" : "NON",
            "divers" => $constatation->getCadDivers(),
        ];
        
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_constatation')."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
        $deploiement = $ctAutreRepository->findOneBy(["nom" => "DEPLOIEMENT"]);
        $dateDeploiement = $deploiement->getAttribut();
        $autreTva = $ctAutreRepository->findOneBy(["nom" => "TVA"]);
        $prixTva = $autreTva->getAttribut();
        $autreTimbre = $ctAutreRepository->findOneBy(["nom" => "TIMBRE"]);
        $prixTimbre = $autreTimbre->getAttribut();
        $timbre = floatval($prixTimbre);
        $nombreConstatations = 0;
        $totalDesDroits = 0;
        $totalDesPrixPv = 0;
        $totalDesTVA = 0;
        $totalDesTimbres = 0;
        $montantTotal = 0;
        /* if(new \DateTime($dateDeploiement) > $date_of_reception){
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            $liste_receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
        } */
        
        //if($liste_constatations != null){
            //foreach($liste_constatations as $liste){
                $marques = $constatation->getCtConstAvDedCarac();
                $carac = new CtConstAvDedCarac();
                foreach($marques as $mrq){
                    $marque = $mrq->getCtMarqueId();
                    $genre = $mrq->getCtGenreId();
                    $carac = $mrq;
                }
                $tarif = 0;
                $prixPv = 0;
                //$utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
                $genreCategorie = $genre->getCtGenreCategorieId();
                $typeDroit = $ctTypeDroitPTACRepository->findOneBy(["tp_dp_libelle" => "Constatation"]);
                $droits = $ctDroitPTACRepository->findBy(["ct_genre_categorie_id" => $genreCategorie->getId(), "ct_type_droit_ptac_id" => $typeDroit->getId()], ["ct_arrete_prix_id" => "DESC", "dp_prix_max" => "DESC"]);
                foreach($droits as $dt){
                    if(($carac->getCadPoidsTotalCharge() >= ($dt->getDpPrixMin() * 1000)) && ($carac->getCadPoidsTotalCharge() < ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
                        $tarif = $dt->getDpDroit();
                        break;
                    }elseif($dt->getDpPrixMin() <= $dt->getDpPrixMax() && $dt->getDpPrixMin() == 0 && $dt->getDpPrixMax() == 0){
                        $tarif = $dt->getDpDroit();
                        break;
                    }
                }
                $pvId = $ctImprimeTechRepository->findOneBy(["abrev_imprime_tech" => "PVO"]);
                $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
                foreach($arretePvTarif as $apt){
                    $arretePrix = $apt->getCtArretePrixId();
                    if($constatation->getCadCreated() >= $arretePrix->getArtDateApplication()){
                        $prixPv = $apt->getVetPrix();
                        break;
                    }
                }
                $droit = $tarif + $prixPv;
                $tva = ($droit * floatval($prixTva)) / 100;
                $montant = $droit + $tva + $timbre;
                $cad = [
                    "controle_pv" => $constatation->getCadNumero(),
                    "proprietaire" => $constatation->getCadProprietaireNom(),
                    "marque" => $marque->getMrqLibelle(),
                    "genre" => $genre->getGrLibelle(),
                    "ptac" => $carac->getCadPoidsTotalCharge(),
                    "droit" => $tarif,
                    "prix_pv" => $prixPv,
                    "tht" => $droit,
                    "tva" => $tva,
                    "timbre" => $timbre,
                    "montant" => $montant,
                ];
                //$liste_des_constatations->add($cad);
                //$nombreConstatations = $nombreConstatations + 1;
                $totalDesDroits = $totalDesDroits + $tarif;
                $totalDesPrixPv = $totalDesPrixPv + $prixPv;
                $totalDesTHT = $totalDesDroits + $totalDesPrixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;
            //}
        //}

        $html = $this->renderView('ct_app_imprimable/proces_verbal_constatation.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'user' => $this->getUser(),
            'total_des_droits' => $totalDesDroits,
            'total_des_prix_pv' => $prixPv,
            'total_des_tht' => $totalDesTHT,
            'total_des_tva' => $totalDesTVA,
            'total_des_timbres' => $totalDesTimbres,
            'montant_total' => $montantTotal,
            //'ct_receptions' => $liste_des_receptions,
            'constatation' => $constatation_data,
            'constatation_carte_grise_data' => $constatation_carte_grise_data,
            'constatation_corps_du_vehicule_data' => $constatation_corps_du_vehicule_data,
            'constatation_note_descriptive_data' => $constatation_note_descriptive_data,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        /* $dompdf->setPaper('A4', 'landscape'); */
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "PROCES_VERBAL_".$id."_CONST_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("PROCES_VERBAL_".$id."_CONST_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_visite/{id}", name="app_ct_app_imprimable_proces_verbal_visite", methods={"GET", "POST"})
     */
    public function ProcesVerbalVisite(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $visite = $ctVisiteRepository->findOneBy(["id" => $id], ["id" => "DESC"]);
        $carte_grise = $visite->getCtCarteGriseId();
        $vehicule = $carte_grise->getCtVehiculeId();
        $vst = [
            "centre" => $visite->getCtCentreId()->getCtrNom(),
            "province" => $visite->getCtCentreId()->getCtProvinceId()->getPrvNom(),
            "pv" => $visite->getVstNumPv(),
            "date" => $visite->getVstCreated(),
            "nom" => $carte_grise->getCgNom().' '.$carte_grise->getCgPrenom(),
            "adresse" => $carte_grise->getCgAdresse(),
            "telephone" => $carte_grise->getCgPhone(),
            "profession" => $carte_grise->getCgProfession(),
            "immatriculation" => $carte_grise->getCgImmatriculation(),
            "marque" => $vehicule->getCtMarqueId(),
            "commune" => $carte_grise->getCgCommune(),
            "genre" => $vehicule->getCtGenreId(),
            "type" => $vehicule->getVhcType(),
            "carrosserie" => $carte_grise->getCtCarrosserieId(),
            "source_energie" => $carte_grise->getCtSourceEnergieId(),
            "puissance" => $carte_grise->getCgPuissanceAdmin(),
            "num_serie" => $vehicule->getVhcNumSerie(),
            "nbr_assise" => $carte_grise->getCgNbrAssis(),
            "nbr_debout" => $carte_grise->getCgNbrDebout(),
            "num_moteur" => $vehicule->getVhcNumMoteur(),
            "ptac" => $vehicule->getVhcPoidsTotalCharge(),
            "pav" => $vehicule->getVhcPoidsVide(),
            "cu" => $vehicule->getVhcChargeUtile(),
            "annee_mise_circulation" => $carte_grise->getCgMiseEnService(),
            "usage" => $visite->getCtUsageId(),
            "carte_violette" => $carte_grise->getCgNumCarteViolette(),
            "date_carte" => $carte_grise->getCgDateCarteViolette(),
            "licence" => $carte_grise->getCgNumVignette(),
            "date_licence" => $carte_grise->getCgDateVignette(),
            "patente" => $carte_grise->getCgPatente(),
            "ani" => $carte_grise->getCgAni(),
            "aptitude" => $visite->isVstIsApte() ? "APTE" : "INAPTE",
            "verificateur" => $visite->getCtVerificateurId(),
            "operateur" => $visite->getCtUserId(),
            "validite" => $visite->getVstDateExpiration(),
        ];
        $type_visite = $visite->getCtTypeVisiteId();

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_visite_premiere')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
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
        $totalDesPrixCartes = 0;
        $totalDesPrixCarnets = 0;
        $montantTotal = 0;
        
        $tarif = 0;
        
        $liste = $visite;
        $usage = $liste->getCtUsageId();
        $tarif = 0;
        $prixPv = 0;
        $carnet = 0;
        $carte = 0;
        $tva = 0;
        $montant = 0;
        $aptitude = "Inapte";
        //$listes_cartes = $ctVisiteExtraRepository->findOneBy(["" => $liste->getId()]);
        $listes_autre = $liste->getVstExtra();
        $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
        $utilisation = $liste->getCtUtilisationId();
        if($utilisation != $utilisationAdministratif){
            $type_visite_id = $visite->getCtTypeVisiteId();
            $usage_tarif = $ctUsageTarifRepository->findOneBy(["ct_usage_id" => $usage->getId(), "ct_type_visite_id" => $type_visite_id], ["usg_trf_annee" => "DESC"]);
            $tarif = $usage_tarif->getUsgTrfPrix();
            $pvId = $ctImprimeTechRepository->findOneBy(["abrev_imprime_tech" => "PVO"]);
            $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
            foreach($arretePvTarif as $apt){
                $arretePrix = $apt->getCtArretePrixId();
                //if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                if($liste->isVstIsContreVisite() == false){
                    if($liste->getVstCreated() >= $arretePrix->getArtDateApplication()){
                        if($liste->isVstIsApte()){
                            $prixPv = $apt->getVetPrix();
                            //$aptitude = "Apte";
                        } else {
                            $prixPv = 2 * $apt->getVetPrix();
                            //$aptitude = "Inapte";
                        }
                    }
                }
            }
            foreach($listes_autre as $autre){
                $vet = $ctVisiteExtraTarifRepository->findOneBy(["ct_imprime_tech_id" => $autre->getId()], ["vet_annee" => "DESC"]);
                if($autre->getId() == 1){
                    $carnet = $carnet + $vet->getVetPrix();
                } else {
                    $carte = $carte + $vet->getVetPrix();
                }
            }
            
            $droit = $tarif + $prixPv + $carnet + $carte;
            $tva = ($droit * floatval($prixTva)) / 100;
            $montant = $droit + $tva + $timbre;
            
            $nombreReceptions = $nombreReceptions + 1;
            $totalDesDroits = $totalDesDroits + $tarif;
            $totalDesPrixPv = $totalDesPrixPv + $prixPv;
            $totalDesTVA = $totalDesTVA + $tva;
            $totalDesTimbres = $totalDesTimbres + $timbre;
            $montantTotal = $montantTotal + $montant;
            $totalDesPrixCartes = $totalDesPrixCartes + $carte;
            $totalDesPrixCarnets = $totalDesPrixCarnets + $carnet;
        }
        //}
        if($visite->isVstIsContreVisite()){
            $html = $this->renderView('ct_app_imprimable/proces_verbal_visite_contre.html.twig', [
                'logo' => $logo,
                'date' => $date,
                'total_des_droits' => $tarif,
                'total_des_prix_pv' => $prixPv,
                'total_des_tht' => $tarif + $prixPv + $carnet + $carte,
                'total_des_tva' => $tva,
                'total_des_timbres' => $timbre,
                'total_des_carnets' => $carnet,
                'total_des_cartes' => $carte,
                'montant_total' => $montant,
                'ct_visite' => $vst,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            /* $dompdf->setPaper('A4', 'landscape'); */
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_".$id."_CONTRE_VISITE_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_".$id."_CONTRE_VISITE_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } else {
            $html = $this->renderView('ct_app_imprimable/proces_verbal_visite_premiere.html.twig', [
                'logo' => $logo,
                'date' => $date,
                'total_des_droits' => $tarif,
                'total_des_prix_pv' => $prixPv,
                'total_des_tht' => $tarif + $prixPv + $carnet + $carte,
                'total_des_tva' => $tva,
                'total_des_timbres' => $timbre,
                'total_des_carnets' => $carnet,
                'total_des_cartes' => $carte,
                'montant_total' => $montant,
                'ct_visite' => $vst,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            /* $dompdf->setPaper('A4', 'landscape'); */
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_".$id."_VISITE_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_".$id."_VISITE_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        }
    }


    // eto no ametrahana ny reception : par type, duplicata et modification
}
