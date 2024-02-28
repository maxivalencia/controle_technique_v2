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
use App\Repository\CtAutreVenteRepository;
use App\Repository\CtTypeReceptionRepository;
use App\Repository\CtImprimeTechRepository;
use App\Repository\CtDroitPTACRepository;
use App\Repository\CtCentreRepository;
use App\Repository\CtUtilisationRepository;
use App\Repository\CtVehiculeRepository;
use App\Repository\CtTypeVisiteRepository;
use App\Repository\CtUsageTarifRepository;
use App\Repository\CtUsageRepository;
use App\Repository\CtUserRepository;
use App\Repository\CtCarteGriseRepository;
use App\Repository\CtImprimeTechUseRepository;
use App\Repository\CtUsageImprimeTechniqueRepository;
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
use App\Entity\CtGenreCategorie;
use App\Entity\CtImprimeTechUse;
use App\Entity\CtVisite;
use App\Entity\CtMarque;
use App\Entity\CtUser;
use App\Entity\CtImprimeTech;
use App\Form\CtImprimeTechType;
use App\Entity\CtBordereau;
use App\Form\CtBordereauType;
use App\Repository\CtBordereauRepository;
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
            // $liste_receptions = $ctReceptionRepository->findBy(["ct_type_reception_id" => $type_reception_id, "ct_centre_id" => $centre, "rcp_created" => $date_of_reception]);
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
        $filename = "FICHE_DE_CONTROLE_RECEP_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("FICHE_DE_CONTROLE_RECEP_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/fiche_de_controle_constatation", name="app_ct_app_imprimable_fiche_de_controle_constatation", methods={"GET", "POST"})
     */
    public function FicheDeControleConstatation(Request $request, CtConstAvDedCaracRepository $ctConstAvDedCaracRepository, CtConstAvDedRepository $ctConstAvDedRepository, CtCentreRepository $ctCentreRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        //$type_reception = "";
        $date_constatation = new \DateTime();
        $date_of_constatation = new \DateTime();
        //$type_reception_id = new CtTypeReception();
        $centre = new CtCentre();
        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            //$recherche = $rechercheform['ct_type_reception_id'];
            $date_constatation = $rechercheform['date'];
            $date_of_constatation = new \DateTime($date_constatation);
            //$type_reception_id = $ctTypeReceptionRepository->findOneBy(["id" => $recherche]);
            //$type_reception = $type_reception_id->getTprcpLibelle();
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

        $dossier = $this->getParameter('dossier_fiche_de_controle_constatation')."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }
        // teste date, comparaison avant utilisation rcp_num_group
        $deploiement = $ctAutreRepository->findOneBy(["nom" => "DEPLOIEMENT"]);
        $dateDeploiement = $deploiement->getAttribut();
        $liste_constatations = new ArrayCollection();
        //$lst_consts = $ctConstAvDedRepository->findBy(["id" => $centre->getId(), "cad_created" => $date_of_constatation]);
        $lst_consts = $ctConstAvDedRepository->findByFicheDeControle($centre->getId(), $date_of_constatation);
        foreach($lst_consts as $liste){
            $marque = "";
            $carac = $ctConstAvDedCaracRepository->findOneBy(["ctConstAvDeds" => $liste]);
            $marques = $carac->getCtMarqueId();
            foreach($marques as $mrq){
                $marque = $mrq->getMrqLibelle();
            }
            $const = [
                "cadProprietaireNom" => $liste->getCadProprietaireNom(),
                "cadProprietaireAdresse" => $liste->getCadProprietaireAdresse(),
                "cadMarque" => $marque,
                "cadImmatriculation" => $liste->getCadImmatriculation(),
            ];
            $liste_constatations->add($const);
        }
        /* if(new \DateTime($dateDeploiement) > $date_of_constatation){
            $liste_constatations = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            $nomGroup = $date_of_constatation->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            $liste_constatations = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
        } */
        $html = $this->renderView('ct_app_imprimable/fiche_de_controle_constatation.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'province' => $centre->getCtProvinceId()->getPrvNom(),
            'centre' => $centre->getCtrNom(),
            'user' => $this->getUser(),
            //'type' => $type_reception,
            'date_constatation' => $date_of_constatation,
            'ct_constatations' => $liste_constatations,
        ]);
        $dompdf->loadHtml($html);
        /* $dompdf->setPaper('A4', 'portrait'); */
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "FICHE_DE_CONTROLE_CONST_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("FICHE_DE_CONTROLE_CONST_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
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
        $motif_ptac = "";
        if(new \DateTime($dateDeploiement) > $date_of_reception){
            //$liste_receptions = $ctReceptionRepository->findBy(["ct_type_reception_id" => $type_reception_id, "ct_centre_id" => $centre, "rcp_created" => $date_of_reception]);
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }else{
            //$nomGroup = $date_of_reception->format('d').'/'.$date_of_reception->format('m').'/'.$centre->getCtrCode().'/'.$type_reception.'/'.$date->format("Y");
            //$liste_receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $nomGroup, "rcp_is_active" => true]);
            $liste_receptions = $ctReceptionRepository->findByFicheDeControle($type_reception_id->getId(), $centre->getId(), $date_of_reception);
        }
        $liste_des_receptions = new ArrayCollection();
        $tarif = 0;
        if($liste_receptions != null){
            foreach($liste_receptions as $liste){
                $genre = $liste->getCtGenreId();
                $motif = $liste->getCtMotifId();
                $motif_ptac = "";
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
                                if($dt->getDpPrixMax() > 0 && $dt->getDpPrixMin() > 0){
                                    $motif_ptac = $dt->getDpPrixMin().'T < PTAC < '.$dt->getDpPrixMax().'T';
                                }elseif($dt->getDpPrixMin() == 0){
                                    $motif_ptac = 'PTAC < '.$dt->getDpPrixMax().'T';
                                }
                                break;
                            }elseif($dt->getDpPrixMin() <= $dt->getDpPrixMax() && $dt->getDpPrixMin() == 0 && $dt->getDpPrixMax() == 0){
                                $tarif = $dt->getDpDroit();
                                /* if($dt->getDpPrixMax() > 0 && $dt->getDpPrixMin() > 0){
                                    $motif_ptac = ' '.$dt->getDpPrixMin().' < PTAC < '.$dt->getDpPrixMax();
                                }elseif($dt->getDpPrixMin() == 0){
                                    $motif_ptac = ' PTAC < '.$dt->getDpPrixMax();
                                } */
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
                    "motif_ptac" => $motif_ptac,
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
        $array_usages = [];
        foreach($liste_usage as $lstu){
            $usg = [
                "usage" => $lstu->getUsgLibelle(),
                "nombre" => 0,
            ];
            //array_push()
            //$liste_des_usages->add($usg);
            $array_usages[$lstu->getUsgLibelle()] = 0;
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
                $array_usages[$liste->getCtUsageId()->getUsgLibelle()] = $array_usages[$liste->getCtUsageId()->getUsgLibelle()] + 1;
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
        foreach($liste_usage as $lstu){
            $usg = [
                "usage" => $lstu->getUsgLibelle(),
                "nombre" => $array_usages[$lstu->getUsgLibelle()],
            ];
            //array_push()
            $liste_des_usages->add($usg);
            $array_usages[$lstu->getUsgLibelle()] = 0;
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
        $vehicule = $ctVehiculeRepository->findOneBy(["id" => $reception->getCtVehiculeId()], ["id" => "DESC"]);
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
        $reception->setRcpGenere($reception->getRcpGenere() + 1);
        $ctReceptionRepository->add($reception, true);

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
        
        $liste_des_receptions = new ArrayCollection();
        $tarif = 0;
        $liste = $reception;
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
                        foreach($droits as $dt){;
                            if(($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() >= ($dt->getDpPrixMin() * 1000)) && ($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() < ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
                                $tarif = $dt->getDpDroit();
                                break;
                            }elseif($dt->getDpPrixMin() <= $dt->getDpPrixMax() && $dt->getDpPrixMin() == 0 && $dt->getDpPrixMax() == 0){
                                $tarif = $dt->getDpDroit();
                                break;
                            }
                        }
                    }
                    $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
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
                $totalDesTHT = $totalDesDroits + $totalDesPrixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;

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
            'reception' => $reception_data,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        /* $dompdf->setPaper('A4', 'landscape'); */
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "PROCES_VERBAL_".$id."_RECEP_".$reception->getRcpImmatriculation()."_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        //$reception->setRcpGenere($reception->getRcpGenere() + 1);
        $dompdf->stream("PROCES_VERBAL_".$id."_RECEP_".$reception->getRcpImmatriculation()."_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_reception_par_type/{id}", name="app_ct_app_imprimable_proces_verbal_reception_par_type", methods={"GET", "POST"})
     */
    public function ProcesVerbalReceptionParType(Request $request, string $id, CtVehiculeRepository $ctVehiculeRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $identification = $id;
        $liste_receptions = new ArrayCollection();
        $reception = $ctReceptionRepository->findOneBy(["rcp_num_group" => $identification], ["id" => "DESC"]);        
        $type_reception_id = $ctTypeReceptionRepository->findOneBy(["id" => $reception->getCtTypeReceptionId()]);
        $type_reception = $type_reception_id->getTprcpLibelle();
        $centre = $ctCentreRepository->findOneBy(["id" => $reception->getCtCentreId()]);
        $date_of_reception = $reception->getRcpCreated();

        $receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $identification], ["id" => "ASC"]);
        foreach($receptions as $rcp){
            $vehicule = $ctVehiculeRepository->findOneBy(["id" => $rcp->getCtVehiculeId()], ["id" => "DESC"]);
            $reception_data = ["id" => $identification,
                "ct_genre_id" => $vehicule->getCtGenreId()->getGrLibelle(),
                "ct_marque_id" => $vehicule->getCtMarqueId()->getMrqLibelle(),
                "vhc_type" => $vehicule->getVhcType(),
                "vhc_num_serie" => $vehicule->getVhcNumSerie(),
                "vhc_num_moteur" => $vehicule->getVhcNumMoteur(),
                "ct_carrosserie_id" => $rcp->getCtCarrosserieId()->getCrsLibelle(),
                "ct_source_energie_id" => $rcp->getCtSourceEnergieId()->getSreLibelle(),
                "vhc_cylindre" => $vehicule->getVhcCylindre(),
                "vhc_puissance" => $vehicule->getVhcPuissance(),
                "vhc_poids_vide" => $vehicule->getVhcPoidsVide(),
                "vhc_charge_utile" => $vehicule->getVhcChargeUtile(),
                "vhc_poids_total_charge" => $vehicule->getVhcPoidsTotalCharge(),
                "ct_utilisation_id" => $rcp->getCtUtilisationId()->getUtLibelle(),
                "ct_motif_id" => $rcp->getCtMotifId()->getMtfLibelle(),
                "rcp_immatriculation" => $rcp->getRcpImmatriculation(),
                "rcp_proprietaire" => $rcp->getRcpProprietaire(),
                "rcp_profession" => $rcp->getRcpProfession(),
                "rcp_adresse" => $rcp->getRcpAdresse(),
                "rcp_nbr_assis" => $rcp->getRcpNbrAssis(),
                "rcp_ngr_debout" => $rcp->getRcpNgrDebout(),
                "rcp_mise_service" => $rcp->getRcpMiseService(),
                "ct_verificateur_id" => $rcp->getCtVerificateurId()->getUsrNom(),
                "ct_type_reception_id" => $type_reception,
                "ct_centre_id" => $centre->getCtrNom(),
                "ct_province_id" => $centre->getCtProvinceId()->getPrvNom(),
                "rcp_num_pv" => $rcp->getRcpNumPv(),
                "rcp_created" => $rcp->getRcpCreated(),
            ];
            $liste_receptions->add($reception_data);
            $rcp->setRcpGenere($rcp->getRcpGenere() + 1);
            $ctReceptionRepository->add($rcp, true);
        }

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');

        $dossier = $this->getParameter('dossier_reception_par_type')."/".$type_reception."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
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

        $liste_des_receptions = new ArrayCollection();
        $tarif = 0;
        $liste = $reception;
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
                        foreach($droits as $dt){;
                            if(($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() >= ($dt->getDpPrixMin() * 1000)) && ($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() < ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
                                $tarif = $dt->getDpDroit();
                                break;
                            }elseif($dt->getDpPrixMin() <= $dt->getDpPrixMax() && $dt->getDpPrixMin() == 0 && $dt->getDpPrixMax() == 0){
                                $tarif = $dt->getDpDroit();
                                break;
                            }
                        }
                    }
                    $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
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
                $totalDesTHT = $totalDesDroits + $totalDesPrixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;

        $html = $this->renderView('ct_app_imprimable/proces_verbal_reception_par_type.html.twig', [
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
            'receptions' => $liste_receptions,
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

        if($constatation_caracteristique_carte_grise->getCadNumSerieType() != null){
            $constatation_carte_grise_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_carte_grise->getCadPremiereCircule() ? $constatation_caracteristique_carte_grise->getCadPremiereCircule() : '',
                "genre" => $constatation_caracteristique_carte_grise->getCtGenreId()->getGrLibelle() ? $constatation_caracteristique_carte_grise->getCtGenreId()->getGrLibelle() : '',
                "marque" => $constatation_caracteristique_carte_grise->getCtMarqueId()->getMrqLibelle() ? $constatation_caracteristique_carte_grise->getCtMarqueId()->getMrqLibelle() : '',
                "type" => $constatation_caracteristique_carte_grise->getCadTypeCar() ? $constatation_caracteristique_carte_grise->getCadTypeCar() : '',
                "numero_de_serie" => $constatation_caracteristique_carte_grise->getCadNumSerieType() ? $constatation_caracteristique_carte_grise->getCadNumSerieType() : '',
                "numero_moteur" => $constatation_caracteristique_carte_grise->getCadNumMoteur() ? $constatation_caracteristique_carte_grise->getCadNumMoteur() : '',
                "source_energie" => $constatation_caracteristique_carte_grise->getCtSourceEnergieId()->getSreLibelle() ? $constatation_caracteristique_carte_grise->getCtSourceEnergieId()->getSreLibelle() : '',
                "cylindre" => $constatation_caracteristique_carte_grise->getCadCylindre() ? $constatation_caracteristique_carte_grise->getCadCylindre() : '',
                "puissance" => $constatation_caracteristique_carte_grise->getCadPuissance() ? $constatation_caracteristique_carte_grise->getCadPuissance() : '',
                "carrosserie" => $constatation_caracteristique_carte_grise->getCtCarrosserieId()->getCrsLibelle() ? $constatation_caracteristique_carte_grise->getCtCarrosserieId()->getCrsLibelle() : '',
                "nbr_assise" => $constatation_caracteristique_carte_grise->getCadNbrAssis() ? $constatation_caracteristique_carte_grise->getCadNbrAssis() : '',
                "charge_utile" => $constatation_caracteristique_carte_grise->getCadChargeUtile() ? $constatation_caracteristique_carte_grise->getCadChargeUtile() : '',
                "poids_a_vide" => $constatation_caracteristique_carte_grise->getCadPoidsVide() ? $constatation_caracteristique_carte_grise->getCadPoidsVide() : '',
                "poids_total_a_charge" => $constatation_caracteristique_carte_grise->getCadPoidsTotalCharge() ? $constatation_caracteristique_carte_grise->getCadPoidsTotalCharge() : '',
                "longueur" => $constatation_caracteristique_carte_grise->getCadLongueur() ? $constatation_caracteristique_carte_grise->getCadLongueur() : '',
                "largeur" => $constatation_caracteristique_carte_grise->getCadLargeur() ? $constatation_caracteristique_carte_grise->getCadLargeur() : '',
                "hauteur" => $constatation_caracteristique_carte_grise->getCadHauteur() ? $constatation_caracteristique_carte_grise->getCadHauteur() : '',
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

        if($constatation_caracteristique_corps_du_vehicule != null){
            $constatation_corps_du_vehicule_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_corps_du_vehicule->getCadPremiereCircule() ? $constatation_caracteristique_corps_du_vehicule->getCadPremiereCircule() : '',
                "genre" => $constatation_caracteristique_corps_du_vehicule->getCtGenreId()->getGrLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtGenreId()->getGrLibelle() : '',
                "marque" => $constatation_caracteristique_corps_du_vehicule->getCtMarqueId()->getMrqLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtMarqueId()->getMrqLibelle() : '',
                "type" => $constatation_caracteristique_corps_du_vehicule->getCadTypeCar() ? $constatation_caracteristique_corps_du_vehicule->getCadTypeCar() : '',
                "numero_de_serie" => $constatation_caracteristique_corps_du_vehicule->getCadNumSerieType() ? $constatation_caracteristique_corps_du_vehicule->getCadNumSerieType() : '',
                "numero_moteur" => $constatation_caracteristique_corps_du_vehicule->getCadNumMoteur() ? $constatation_caracteristique_corps_du_vehicule->getCadNumMoteur() : '',
                "source_energie" => $constatation_caracteristique_corps_du_vehicule->getCtSourceEnergieId()->getSreLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtSourceEnergieId()->getSreLibelle() : '',
                "cylindre" => $constatation_caracteristique_corps_du_vehicule->getCadCylindre() ? $constatation_caracteristique_corps_du_vehicule->getCadCylindre() : '',
                "puissance" => $constatation_caracteristique_corps_du_vehicule->getCadPuissance() ? $constatation_caracteristique_corps_du_vehicule->getCadPuissance() : '',
                "carrosserie" => $constatation_caracteristique_corps_du_vehicule->getCtCarrosserieId()->getCrsLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtCarrosserieId()->getCrsLibelle() : '',
                "nbr_assise" => $constatation_caracteristique_corps_du_vehicule->getCadNbrAssis() ? $constatation_caracteristique_corps_du_vehicule->getCadNbrAssis() : '',
                "charge_utile" => $constatation_caracteristique_corps_du_vehicule->getCadChargeUtile() ? $constatation_caracteristique_corps_du_vehicule->getCadChargeUtile() : '',
                "poids_a_vide" => $constatation_caracteristique_corps_du_vehicule->getCadPoidsVide() ? $constatation_caracteristique_corps_du_vehicule->getCadPoidsVide() : '',
                "poids_total_a_charge" => $constatation_caracteristique_corps_du_vehicule->getCadPoidsTotalCharge() ? $constatation_caracteristique_corps_du_vehicule->getCadPoidsTotalCharge() : '',
                "longueur" => $constatation_caracteristique_corps_du_vehicule->getCadLongueur() ? $constatation_caracteristique_corps_du_vehicule->getCadLongueur() : '',
                "largeur" => $constatation_caracteristique_corps_du_vehicule->getCadLargeur() ? $constatation_caracteristique_corps_du_vehicule->getCadLargeur() : '',
                "hauteur" => $constatation_caracteristique_corps_du_vehicule->getCadHauteur() ? $constatation_caracteristique_corps_du_vehicule->getCadHauteur() : '',
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

        if($constatation_caracteristique_note_descriptive != null){
            $constatation_note_descriptive_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_note_descriptive->getCadPremiereCircule() ? $constatation_caracteristique_note_descriptive->getCadPremiereCircule() : '',
                "genre" => $constatation_caracteristique_note_descriptive->getCtGenreId()->getGrLibelle() ? $constatation_caracteristique_note_descriptive->getCtGenreId()->getGrLibelle() : '',
                "marque" => $constatation_caracteristique_note_descriptive->getCtMarqueId()->getMrqLibelle() ? $constatation_caracteristique_note_descriptive->getCtMarqueId()->getMrqLibelle() : '',
                "type" => $constatation_caracteristique_note_descriptive->getCadTypeCar() ? $constatation_caracteristique_note_descriptive->getCadTypeCar() : '',
                "numero_de_serie" => $constatation_caracteristique_note_descriptive->getCadNumSerieType() ? $constatation_caracteristique_note_descriptive->getCadNumSerieType() : '',
                "numero_moteur" => $constatation_caracteristique_note_descriptive->getCadNumMoteur() ? $constatation_caracteristique_note_descriptive->getCadNumMoteur() : '',
                "source_energie" => $constatation_caracteristique_note_descriptive->getCtSourceEnergieId()->getSreLibelle() ? $constatation_caracteristique_note_descriptive->getCtSourceEnergieId()->getSreLibelle() : '',
                "cylindre" => $constatation_caracteristique_note_descriptive->getCadCylindre() ? $constatation_caracteristique_note_descriptive->getCadCylindre() : '',
                "puissance" => $constatation_caracteristique_note_descriptive->getCadPuissance() ? $constatation_caracteristique_note_descriptive->getCadPuissance() : '',
                "carrosserie" => $constatation_caracteristique_note_descriptive->getCtCarrosserieId()->getCrsLibelle() ? $constatation_caracteristique_note_descriptive->getCtCarrosserieId()->getCrsLibelle() : '',
                "nbr_assise" => $constatation_caracteristique_note_descriptive->getCadNbrAssis() ? $constatation_caracteristique_note_descriptive->getCadNbrAssis() : '',
                "charge_utile" => $constatation_caracteristique_note_descriptive->getCadChargeUtile() ? $constatation_caracteristique_note_descriptive->getCadChargeUtile() : '',
                "poids_a_vide" => $constatation_caracteristique_note_descriptive->getCadPoidsVide() ? $constatation_caracteristique_note_descriptive->getCadPoidsVide() : '',
                "poids_total_a_charge" => $constatation_caracteristique_note_descriptive->getCadPoidsTotalCharge() ? $constatation_caracteristique_note_descriptive->getCadPoidsTotalCharge() : '',
                "longueur" => $constatation_caracteristique_note_descriptive->getCadLongueur() ? $constatation_caracteristique_note_descriptive->getCadLongueur() : '',
                "largeur" => $constatation_caracteristique_note_descriptive->getCadLargeur() ? $constatation_caracteristique_note_descriptive->getCadLargeur() : '',
                "hauteur" => $constatation_caracteristique_note_descriptive->getCadHauteur() ? $constatation_caracteristique_note_descriptive->getCadHauteur() : '',
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

        $constatation->setCadGenere($constatation->getCadGenere() + 1);
        $ctConstAvDedRepository->add($constatation, true);

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
                $marques = $constatation->getCtConstAvDedCarac();
                $carac = new CtConstAvDedCarac();
                $genre = new CtGenreCategorie();
                foreach($marques as $mrq){
                    $marque = $mrq->getCtMarqueId();
                    $genre = $mrq->getCtGenreId();
                    $carac = $mrq;
                }
                $tarif = 0;
                $prixPv = 0;
                $genreCategorie = $genre->getCtGenreCategorieId();
                $typeDroit = $ctTypeDroitPTACRepository->findOneBy(["id" => 2]);
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
                $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
                $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
                foreach($arretePvTarif as $apt){
                    $arretePrix = $apt->getCtArretePrixId();
                    //if($constatation->getCadCreated() >= $arretePrix->getArtDateApplication()){
                    // secours fotsiny  new date time fa mila atao daten'ilay pv no tena izy
                    if(new \DateTime() >= $arretePrix->getArtDateApplication()){
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
                $totalDesDroits = $totalDesDroits + $tarif;
                $totalDesPrixPv = $totalDesPrixPv + $prixPv;
                $totalDesTHT = $totalDesDroits + $totalDesPrixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;

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
        $filename = "PROCES_VERBAL_".$id."_CONST_".$constatation->getCadImmatriculation()."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("PROCES_VERBAL_".$id."_CONST_".$constatation->getCadImmatriculation()."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_visite/{id}", name="app_ct_app_imprimable_proces_verbal_visite", methods={"GET", "POST"})
     */
    public function ProcesVerbalVisite(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $visite = $ctVisiteRepository->findOneBy(["id" => $id]);
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
            "reparation" => $visite->getVstDureeReparation(),
        ];
        $type_visite = $visite->getCtTypeVisiteId();

        $visite->setVstGenere($visite->getVstGenere() + 1);
        $ctVisiteRepository->add($visite, true);

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');
        if($visite->isVstIsContreVisite()){
            $dossier = $this->getParameter('dossier_visite_contre')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
        } else {
            $dossier = $this->getParameter('dossier_visite_premiere')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
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
        $listes_autre = $liste->getVstExtra();
        $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
        $utilisation = $liste->getCtUtilisationId();
        if($utilisation != $utilisationAdministratif){
            $type_visite_id = $visite->getCtTypeVisiteId();
            $usage_tarif = $ctUsageTarifRepository->findOneBy(["ct_usage_id" => $usage->getId(), "ct_type_visite_id" => $type_visite_id], ["usg_trf_annee" => "DESC"]);
            $tarif = $usage_tarif->getUsgTrfPrix();
            $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
            $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
            foreach($arretePvTarif as $apt){
                $arretePrix = $apt->getCtArretePrixId();
                if($liste->isVstIsContreVisite() == false){
                    //if($liste->getVstCreated() >= $arretePrix->getArtDateApplication()){
                    // secours fotsiny  new date time fa mila atao daten'ilay pv no tena izy
                    if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        if($liste->isVstIsApte() == true){
                            $prixPv = $apt->getVetPrix();
                        } else {
                            $prixPv = 2 * $apt->getVetPrix();
                        }
                        break;
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } elseif($visite->isVstIsApte()) {
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } else {
            $html = $this->renderView('ct_app_imprimable/proces_verbal_visite_inapte.html.twig', [
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
                'visite' => $visite,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        }
    }

    /**
     * @Route("/fiche_verificateur", name="app_ct_app_imprimable_fiche_verificateur", methods={"GET", "POST"})
     */
    public function FicheVerificateur(Request $request, CtUserRepository $ctUserRepository, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $type_visite = "";
        $date_visite = new \DateTime();
        $date_of_visite = new \DateTime();
        $type_visite_id = new CtTypeReception();
        $centre = new CtCentre();
        $verificateur = new CtUser();

        $liste_usage = $ctUsageRepository->findAll();
        $liste_des_usages = new ArrayCollection();
        foreach($liste_usage as $lstu){
            $usg = [
                "usage" => $lstu->getUsgLibelle(),
                "nombre" => 0,
            ];
            $liste_des_usages->add($usg);
        }

        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            $recherche = $rechercheform['ct_user_id'];
            $date_visite = $rechercheform['date'];
            $date_of_visite = new \DateTime($date_visite);
            $verificateur = $ctUserRepository->findOneBy(["id" => $recherche]);
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

        $dossier = $this->getParameter('dossier_fiche_verificateur')."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
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
        $apte = 0;
        $inapte = 0;
        if(new \DateTime($dateDeploiement) > $date_of_visite){
            // $liste_visites = $ctVisiteRepository->findByFicheDeControle($type_visite_id->getId(), $centre->getId(), $date_of_visite);
            $liste_visites = $ctVisiteRepository->findBy(["ct_verificateur_id" => $verificateur, "ct_centre_id" => $centre, "vst_created" => $date_of_visite], ["id" => "ASC"]);
        }else{
            $nomGroup = $date_of_visite->format('d').'/'.$date_of_visite->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$type_visite.'/'.$date_of_visite->format("Y");
            //$liste_visites = $ctVisiteRepository->findBy(["vst_num_feuille_caisse" => $nomGroup, "vst_is_active" => true]);
            $liste_visites = $ctVisiteRepository->findBy(["ct_verificateur_id" => $verificateur, "ct_centre_id" => $centre, "vst_created" => $date_of_visite], ["id" => "ASC"]);
        }
        $liste_des_visites = new ArrayCollection();
        $tarif = 0;
        if($liste_visites != null){
            foreach($liste_visites as $liste){
                if($liste->isVstIsContreVisite() == true){
                    continue;
                }
                $usage = $liste->getCtUsageId();
                $tarif = 0;
                $prixPv = 0;
                $carnet = 0;
                $carte = 0;
                $aptitude = "Inapte";
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
                                    $apte = $apte + 1;
                                } else {
                                    $prixPv = 2 * $apt->getVetPrix();
                                    $aptitude = "Inapte";
                                    $inapte = $inapte + 1;
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
                    if($liste_des_usages[$compteur_usage]["usage"] == $liste->getCtUsageId()->getUsgLibelle()){
                        $ldu["nombre"]++;
                    }
                    $compteur_usage++;
                }

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

        $html = $this->renderView('ct_app_imprimable/fiche_verificateur.html.twig', [
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
            'ct_visites' => $liste_visites,
            'liste_usage' => $liste_des_usages,
            'verificateur' => $verificateur->getUsrNom(),
            'nbr_apte' => $apte,
            'nbr_inapte' => $inapte,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "FICHE_VERIFICATEUR_".$verificateur."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("FICHE_VERIFICATEUR_".$verificateur."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/liste_anomalies", name="app_ct_app_imprimable_liste_anomalies", methods={"GET", "POST"})
     */
    public function ListeAnomalies(Request $request, CtUserRepository $ctUserRepository, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $type_visite = "";
        $date_visite = new \DateTime();
        $date_of_visite = new \DateTime();
        $type_visite_id = new CtTypeReception();
        $centre = new CtCentre();
        $verificateur = new CtUser();

        $liste_usage = $ctUsageRepository->findAll();
        $liste_des_usages = new ArrayCollection();
        foreach($liste_usage as $lstu){
            $usg = [
                "usage" => $lstu->getUsgLibelle(),
                "nombre" => 0,
            ];
            $liste_des_usages->add($usg);
        }

        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            $date_visite = $rechercheform['date'];
            $date_of_visite = new \DateTime($date_visite);
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

        $dossier = $this->getParameter('dossier_liste_anomalie')."/".$centre->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
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
        $apte = 0;
        $inapte = 0;
        if(new \DateTime($dateDeploiement) > $date_of_visite){
            // $liste_visites = $ctVisiteRepository->findByFicheDeControle($type_visite_id->getId(), $centre->getId(), $date_of_visite);
            $liste_visites = $ctVisiteRepository->findBy(["vst_is_apte" => 0, "ct_centre_id" => $centre, "vst_created" => $date_of_visite], ["id" => "ASC"]);
        }else{
            $nomGroup = $date_of_visite->format('d').'/'.$date_of_visite->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$type_visite.'/'.$date_of_visite->format("Y");
            //$liste_visites = $ctVisiteRepository->findBy(["vst_num_feuille_caisse" => $nomGroup, "vst_is_active" => true]);
            $liste_visites = $ctVisiteRepository->findBy(["vst_is_apte" => 0, "ct_centre_id" => $centre, "vst_created" => $date_of_visite], ["id" => "ASC"]);
        }
        $liste_des_visites = new ArrayCollection();
        $tarif = 0;
        if($liste_visites != null){
            foreach($liste_visites as $liste){
                if($liste->isVstIsContreVisite() == true){
                    continue;
                }
                $usage = $liste->getCtUsageId();
                $tarif = 0;
                $prixPv = 0;
                $carnet = 0;
                $carte = 0;
                $aptitude = "Inapte";
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
                        if($liste->isVstIsContreVisite() == false){
                            if($liste->getVstCreated() >= $arretePrix->getArtDateApplication()){
                                if($liste->isVstIsApte()){
                                    $prixPv = $apt->getVetPrix();
                                    $aptitude = "Apte";
                                    $apte = $apte + 1;
                                } else {
                                    $prixPv = 2 * $apt->getVetPrix();
                                    $aptitude = "Inapte";
                                    $inapte = $inapte + 1;
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
                    if($liste_des_usages[$compteur_usage]["usage"] == $liste->getCtUsageId()->getUsgLibelle()){
                        $ldu["nombre"]++;
                    }
                    $compteur_usage++;
                }

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

        $html = $this->renderView('ct_app_imprimable/liste_anomalie.html.twig', [
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
            'ct_visites' => $liste_visites,
            'liste_usage' => $liste_des_usages,
            'verificateur' => $verificateur->getUsrNom(),
            'nbr_apte' => $apte,
            'nbr_inapte' => $inapte,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "LISTE_ANOMALIES_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("LISTE_ANOMALIES_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_reception_duplicata/{id}", name="app_ct_app_imprimable_proces_verbal_reception_duplicata", methods={"GET", "POST"})
     */
    public function ProcesVerbalReceptionDuplicata(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $identification = intval($id);
        $reception = $ctReceptionRepository->findOneBy(["id" => $identification], ["id" => "DESC"]);
        $vehicule = $ctVehiculeRepository->findOneBy(["id" => $reception->getCtVehiculeId()], ["id" => "DESC"]);
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
        $reception->setRcpGenere($reception->getRcpGenere() + 1);
        $ctReceptionRepository->add($reception, true);

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
        
        $liste_des_receptions = new ArrayCollection();
        $tarif = 0;
        $liste = $reception;
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
                        foreach($droits as $dt){;
                            if(($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() >= ($dt->getDpPrixMin() * 1000)) && ($liste->getCtVehiculeId()->getVhcPoidsTotalCharge() < ($dt->getDpPrixMax() * 1000)) && ($dt->getDpPrixMin() <= $dt->getDpPrixMax())){
                                $tarif = $dt->getDpDroit();
                                break;
                            }elseif($dt->getDpPrixMin() <= $dt->getDpPrixMax() && $dt->getDpPrixMin() == 0 && $dt->getDpPrixMax() == 0){
                                $tarif = $dt->getDpDroit();
                                break;
                            }
                        }
                    }
                    $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
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
                $totalDesTHT = $totalDesDroits + $totalDesPrixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;

        $html = $this->renderView('ct_app_imprimable/pv_duplicata_reception.html.twig', [
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
            'reception' => $reception_data,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        /* $dompdf->setPaper('A4', 'landscape'); */
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "PROCES_VERBAL_DUPLICATA_".$id."_RECEP_".$reception->getRcpImmatriculation()."_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        //$reception->setRcpGenere($reception->getRcpGenere() + 1);
        $dompdf->stream("PROCES_VERBAL_DUPLICATA_".$id."_RECEP_".$reception->getRcpImmatriculation()."_".$type_reception."_".$centre->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_constatation_duplicata/{id}", name="app_ct_app_imprimable_proces_verbal_constatation_duplicata", methods={"GET", "POST"})
     */
    public function ProcesVerbalConstatationDuplicata(Request $request, int $id, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository, CtConstAvDedCaracRepository $ctConstAvDedCaracRepository, CtConstAvDedRepository $ctConstAvDedRepository, CtVehiculeRepository $ctVehiculeRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
    {
        $identification = intval($id);
        $constatation = $ctConstAvDedRepository->findOneBy(["id" => $identification], ["id" => "DESC"]);
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

        if($constatation_caracteristique_carte_grise->getCadNumSerieType() != null){
            $constatation_carte_grise_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_carte_grise->getCadPremiereCircule() ? $constatation_caracteristique_carte_grise->getCadPremiereCircule() : '',
                "genre" => $constatation_caracteristique_carte_grise->getCtGenreId()->getGrLibelle() ? $constatation_caracteristique_carte_grise->getCtGenreId()->getGrLibelle() : '',
                "marque" => $constatation_caracteristique_carte_grise->getCtMarqueId()->getMrqLibelle() ? $constatation_caracteristique_carte_grise->getCtMarqueId()->getMrqLibelle() : '',
                "type" => $constatation_caracteristique_carte_grise->getCadTypeCar() ? $constatation_caracteristique_carte_grise->getCadTypeCar() : '',
                "numero_de_serie" => $constatation_caracteristique_carte_grise->getCadNumSerieType() ? $constatation_caracteristique_carte_grise->getCadNumSerieType() : '',
                "numero_moteur" => $constatation_caracteristique_carte_grise->getCadNumMoteur() ? $constatation_caracteristique_carte_grise->getCadNumMoteur() : '',
                "source_energie" => $constatation_caracteristique_carte_grise->getCtSourceEnergieId()->getSreLibelle() ? $constatation_caracteristique_carte_grise->getCtSourceEnergieId()->getSreLibelle() : '',
                "cylindre" => $constatation_caracteristique_carte_grise->getCadCylindre() ? $constatation_caracteristique_carte_grise->getCadCylindre() : '',
                "puissance" => $constatation_caracteristique_carte_grise->getCadPuissance() ? $constatation_caracteristique_carte_grise->getCadPuissance() : '',
                "carrosserie" => $constatation_caracteristique_carte_grise->getCtCarrosserieId()->getCrsLibelle() ? $constatation_caracteristique_carte_grise->getCtCarrosserieId()->getCrsLibelle() : '',
                "nbr_assise" => $constatation_caracteristique_carte_grise->getCadNbrAssis() ? $constatation_caracteristique_carte_grise->getCadNbrAssis() : '',
                "charge_utile" => $constatation_caracteristique_carte_grise->getCadChargeUtile() ? $constatation_caracteristique_carte_grise->getCadChargeUtile() : '',
                "poids_a_vide" => $constatation_caracteristique_carte_grise->getCadPoidsVide() ? $constatation_caracteristique_carte_grise->getCadPoidsVide() : '',
                "poids_total_a_charge" => $constatation_caracteristique_carte_grise->getCadPoidsTotalCharge() ? $constatation_caracteristique_carte_grise->getCadPoidsTotalCharge() : '',
                "longueur" => $constatation_caracteristique_carte_grise->getCadLongueur() ? $constatation_caracteristique_carte_grise->getCadLongueur() : '',
                "largeur" => $constatation_caracteristique_carte_grise->getCadLargeur() ? $constatation_caracteristique_carte_grise->getCadLargeur() : '',
                "hauteur" => $constatation_caracteristique_carte_grise->getCadHauteur() ? $constatation_caracteristique_carte_grise->getCadHauteur() : '',
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

        if($constatation_caracteristique_corps_du_vehicule != null){
            $constatation_corps_du_vehicule_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_corps_du_vehicule->getCadPremiereCircule() ? $constatation_caracteristique_corps_du_vehicule->getCadPremiereCircule() : '',
                "genre" => $constatation_caracteristique_corps_du_vehicule->getCtGenreId()->getGrLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtGenreId()->getGrLibelle() : '',
                "marque" => $constatation_caracteristique_corps_du_vehicule->getCtMarqueId()->getMrqLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtMarqueId()->getMrqLibelle() : '',
                "type" => $constatation_caracteristique_corps_du_vehicule->getCadTypeCar() ? $constatation_caracteristique_corps_du_vehicule->getCadTypeCar() : '',
                "numero_de_serie" => $constatation_caracteristique_corps_du_vehicule->getCadNumSerieType() ? $constatation_caracteristique_corps_du_vehicule->getCadNumSerieType() : '',
                "numero_moteur" => $constatation_caracteristique_corps_du_vehicule->getCadNumMoteur() ? $constatation_caracteristique_corps_du_vehicule->getCadNumMoteur() : '',
                "source_energie" => $constatation_caracteristique_corps_du_vehicule->getCtSourceEnergieId()->getSreLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtSourceEnergieId()->getSreLibelle() : '',
                "cylindre" => $constatation_caracteristique_corps_du_vehicule->getCadCylindre() ? $constatation_caracteristique_corps_du_vehicule->getCadCylindre() : '',
                "puissance" => $constatation_caracteristique_corps_du_vehicule->getCadPuissance() ? $constatation_caracteristique_corps_du_vehicule->getCadPuissance() : '',
                "carrosserie" => $constatation_caracteristique_corps_du_vehicule->getCtCarrosserieId()->getCrsLibelle() ? $constatation_caracteristique_corps_du_vehicule->getCtCarrosserieId()->getCrsLibelle() : '',
                "nbr_assise" => $constatation_caracteristique_corps_du_vehicule->getCadNbrAssis() ? $constatation_caracteristique_corps_du_vehicule->getCadNbrAssis() : '',
                "charge_utile" => $constatation_caracteristique_corps_du_vehicule->getCadChargeUtile() ? $constatation_caracteristique_corps_du_vehicule->getCadChargeUtile() : '',
                "poids_a_vide" => $constatation_caracteristique_corps_du_vehicule->getCadPoidsVide() ? $constatation_caracteristique_corps_du_vehicule->getCadPoidsVide() : '',
                "poids_total_a_charge" => $constatation_caracteristique_corps_du_vehicule->getCadPoidsTotalCharge() ? $constatation_caracteristique_corps_du_vehicule->getCadPoidsTotalCharge() : '',
                "longueur" => $constatation_caracteristique_corps_du_vehicule->getCadLongueur() ? $constatation_caracteristique_corps_du_vehicule->getCadLongueur() : '',
                "largeur" => $constatation_caracteristique_corps_du_vehicule->getCadLargeur() ? $constatation_caracteristique_corps_du_vehicule->getCadLargeur() : '',
                "hauteur" => $constatation_caracteristique_corps_du_vehicule->getCadHauteur() ? $constatation_caracteristique_corps_du_vehicule->getCadHauteur() : '',
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

        if($constatation_caracteristique_note_descriptive != null){
            $constatation_note_descriptive_data = [
                "date_premiere_mise_en_circulation" => $constatation_caracteristique_note_descriptive->getCadPremiereCircule() ? $constatation_caracteristique_note_descriptive->getCadPremiereCircule() : '',
                "genre" => $constatation_caracteristique_note_descriptive->getCtGenreId()->getGrLibelle() ? $constatation_caracteristique_note_descriptive->getCtGenreId()->getGrLibelle() : '',
                "marque" => $constatation_caracteristique_note_descriptive->getCtMarqueId()->getMrqLibelle() ? $constatation_caracteristique_note_descriptive->getCtMarqueId()->getMrqLibelle() : '',
                "type" => $constatation_caracteristique_note_descriptive->getCadTypeCar() ? $constatation_caracteristique_note_descriptive->getCadTypeCar() : '',
                "numero_de_serie" => $constatation_caracteristique_note_descriptive->getCadNumSerieType() ? $constatation_caracteristique_note_descriptive->getCadNumSerieType() : '',
                "numero_moteur" => $constatation_caracteristique_note_descriptive->getCadNumMoteur() ? $constatation_caracteristique_note_descriptive->getCadNumMoteur() : '',
                "source_energie" => $constatation_caracteristique_note_descriptive->getCtSourceEnergieId()->getSreLibelle() ? $constatation_caracteristique_note_descriptive->getCtSourceEnergieId()->getSreLibelle() : '',
                "cylindre" => $constatation_caracteristique_note_descriptive->getCadCylindre() ? $constatation_caracteristique_note_descriptive->getCadCylindre() : '',
                "puissance" => $constatation_caracteristique_note_descriptive->getCadPuissance() ? $constatation_caracteristique_note_descriptive->getCadPuissance() : '',
                "carrosserie" => $constatation_caracteristique_note_descriptive->getCtCarrosserieId()->getCrsLibelle() ? $constatation_caracteristique_note_descriptive->getCtCarrosserieId()->getCrsLibelle() : '',
                "nbr_assise" => $constatation_caracteristique_note_descriptive->getCadNbrAssis() ? $constatation_caracteristique_note_descriptive->getCadNbrAssis() : '',
                "charge_utile" => $constatation_caracteristique_note_descriptive->getCadChargeUtile() ? $constatation_caracteristique_note_descriptive->getCadChargeUtile() : '',
                "poids_a_vide" => $constatation_caracteristique_note_descriptive->getCadPoidsVide() ? $constatation_caracteristique_note_descriptive->getCadPoidsVide() : '',
                "poids_total_a_charge" => $constatation_caracteristique_note_descriptive->getCadPoidsTotalCharge() ? $constatation_caracteristique_note_descriptive->getCadPoidsTotalCharge() : '',
                "longueur" => $constatation_caracteristique_note_descriptive->getCadLongueur() ? $constatation_caracteristique_note_descriptive->getCadLongueur() : '',
                "largeur" => $constatation_caracteristique_note_descriptive->getCadLargeur() ? $constatation_caracteristique_note_descriptive->getCadLargeur() : '',
                "hauteur" => $constatation_caracteristique_note_descriptive->getCadHauteur() ? $constatation_caracteristique_note_descriptive->getCadHauteur() : '',
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

        $constatation->setCadGenere($constatation->getCadGenere() + 1);
        $ctConstAvDedRepository->add($constatation, true);

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
                $marques = $constatation->getCtConstAvDedCarac();
                $carac = new CtConstAvDedCarac();
                $genre = new CtGenreCategorie();
                foreach($marques as $mrq){
                    $marque = $mrq->getCtMarqueId();
                    $genre = $mrq->getCtGenreId();
                    $carac = $mrq;
                }
                $tarif = 0;
                $prixPv = 0;
                $genreCategorie = $genre->getCtGenreCategorieId();
                $typeDroit = $ctTypeDroitPTACRepository->findOneBy(["id" => 2]);
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
                $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
                $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
                foreach($arretePvTarif as $apt){
                    $arretePrix = $apt->getCtArretePrixId();
                    //if($constatation->getCadCreated() >= $arretePrix->getArtDateApplication()){
                    // secours fotsiny  new date time fa mila atao daten'ilay pv no tena izy
                    if(new \DateTime() >= $arretePrix->getArtDateApplication()){
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
                $totalDesDroits = $totalDesDroits + $tarif;
                $totalDesPrixPv = $totalDesPrixPv + $prixPv;
                $totalDesTHT = $totalDesDroits + $totalDesPrixPv;
                $totalDesTVA = $totalDesTVA + $tva;
                $totalDesTimbres = $totalDesTimbres + $timbre;
                $montantTotal = $montantTotal + $montant;

        $html = $this->renderView('ct_app_imprimable/pv_duplicata_constatation.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'user' => $this->getUser(),
            'total_des_droits' => $totalDesDroits,
            'total_des_prix_pv' => $prixPv,
            'total_des_tht' => $totalDesTHT,
            'total_des_tva' => $totalDesTVA,
            'total_des_timbres' => $totalDesTimbres,
            'montant_total' => $montantTotal,
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
        $filename = "PROCES_VERBAL_DUPLICATA_".$id."_CONST_".$constatation->getCadImmatriculation()."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("PROCES_VERBAL_DUPLICATA_".$id."_CONST_".$constatation->getCadImmatriculation()."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/proces_verbal_visite_duplicata/{id}", name="app_ct_app_imprimable_proces_verbal_visite_duplicata", methods={"GET", "POST"})
     */
    public function ProcesVerbalVisiteDuplicata(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
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
            "reparation" => $visite->getVstDureeReparation(),
        ];
        $type_visite = $visite->getCtTypeVisiteId();

        $visite->setVstGenere($visite->getVstGenere() + 1);
        $ctVisiteRepository->add($visite, true);

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');
        if($visite->isVstIsContreVisite()){
            $dossier = $this->getParameter('dossier_visite_contre')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
        } else {
            $dossier = $this->getParameter('dossier_visite_premiere')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
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
        $listes_autre = $liste->getVstExtra();
        $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
        $utilisation = $liste->getCtUtilisationId();
        if($utilisation != $utilisationAdministratif){
            $type_visite_id = $visite->getCtTypeVisiteId();
            $usage_tarif = $ctUsageTarifRepository->findOneBy(["ct_usage_id" => $usage->getId(), "ct_type_visite_id" => $type_visite_id], ["usg_trf_annee" => "DESC"]);
            $tarif = $usage_tarif->getUsgTrfPrix();
            $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
            $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
            foreach($arretePvTarif as $apt){
                $arretePrix = $apt->getCtArretePrixId();
                if($liste->isVstIsContreVisite() == false){
                    //if($liste->getVstCreated() >= $arretePrix->getArtDateApplication()){
                    // secours fotsiny  new date time fa mila atao daten'ilay pv no tena izy
                    if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        if($liste->isVstIsApte() == true){
                            $prixPv = $apt->getVetPrix();
                        } else {
                            $prixPv = 2 * $apt->getVetPrix();
                        }
                        break;
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
            $html = $this->renderView('ct_app_imprimable/pv_duplicata_visite_contre.html.twig', [
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_DUPLICATA_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_DUPLICATA_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } elseif($visite->isVstIsApte()) {
            $html = $this->renderView('ct_app_imprimable/pv_duplicata_visite_premiere.html.twig', [
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_DUPLICATA_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_DUPLICATA_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } else {
            $html = $this->renderView('ct_app_imprimable/proces_verbal_visite_inapte.html.twig', [
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
                'visite' => $visite,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_DUPLICATA_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_DUPLICATA_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        }
    }

    /**
     * @Route("/proces_verbal_visite_mutation/{id}", name="app_ct_app_imprimable_proces_verbal_visite_mutation", methods={"GET", "POST"})
     */
    public function ProcesVerbalVisiteMutation(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
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
            "reparation" => $visite->getVstDureeReparation(),
        ];
        $type_visite = $visite->getCtTypeVisiteId();

        $visite->setVstGenere($visite->getVstGenere() + 1);
        $ctVisiteRepository->add($visite, true);

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');
        if($visite->isVstIsContreVisite()){
            $dossier = $this->getParameter('dossier_visite_contre')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
        } else {
            $dossier = $this->getParameter('dossier_visite_premiere')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
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
        $listes_autre = $liste->getVstExtra();
        $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
        $utilisation = $liste->getCtUtilisationId();
        if($utilisation != $utilisationAdministratif){
            $type_visite_id = $visite->getCtTypeVisiteId();
            $usage_tarif = $ctUsageTarifRepository->findOneBy(["ct_usage_id" => $usage->getId(), "ct_type_visite_id" => $type_visite_id], ["usg_trf_annee" => "DESC"]);
            $tarif = $usage_tarif->getUsgTrfPrix();
            $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
            $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
            foreach($arretePvTarif as $apt){
                $arretePrix = $apt->getCtArretePrixId();
                if($liste->isVstIsContreVisite() == false){
                    //if($liste->getVstCreated() >= $arretePrix->getArtDateApplication()){
                    // secours fotsiny  new date time fa mila atao daten'ilay pv no tena izy
                    if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        if($liste->isVstIsApte() == true){
                            $prixPv = $apt->getVetPrix();
                        } else {
                            $prixPv = 2 * $apt->getVetPrix();
                        }
                        break;
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
            $html = $this->renderView('ct_app_imprimable/pv_mutation_visite_contre.html.twig', [
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_MUTATION_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_MUTATION_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } elseif($visite->isVstIsApte()) {
            $html = $this->renderView('ct_app_imprimable/pv_mutation_visite_premiere.html.twig', [
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_MUTATION_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_MUTATION_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } else {
            $html = $this->renderView('ct_app_imprimable/proces_verbal_visite_inapte.html.twig', [
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
                'visite' => $visite,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_MUTATION_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_MUTATION_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        }
    }

    /**
     * @Route("/proces_verbal_visite_modification/{id}", name="app_ct_app_imprimable_proces_verbal_visite_modification", methods={"GET", "POST"})
     */
    public function ProcesVerbalVisiteModification(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtUsageRepository $ctUsageRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageTarifRepository $ctUsageTarifRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtCentreRepository $ctCentreRepository, CtDroitPTACRepository $ctDroitPTACRepository, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtMotifTarifRepository $ctMotifTarifRepository, CtTypeReceptionRepository $ctTypeReceptionRepository, CtReceptionRepository $ctReceptionRepository, CtAutreRepository $ctAutreRepository)//: Response
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
            "reparation" => $visite->getVstDureeReparation(),
        ];
        $type_visite = $visite->getCtTypeVisiteId();

        $visite->setVstGenere($visite->getVstGenere() + 1);
        $ctVisiteRepository->add($visite, true);

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');
        if($visite->isVstIsContreVisite()){
            $dossier = $this->getParameter('dossier_visite_contre')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
        } else {
            $dossier = $this->getParameter('dossier_visite_premiere')."/".$type_visite."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }
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
        $listes_autre = $liste->getVstExtra();
        $utilisationAdministratif = $ctUtilisationRepository->findOneBy(["ut_libelle" => "Administratif"]);
        $utilisation = $liste->getCtUtilisationId();
        if($utilisation != $utilisationAdministratif){
            $type_visite_id = $visite->getCtTypeVisiteId();
            $usage_tarif = $ctUsageTarifRepository->findOneBy(["ct_usage_id" => $usage->getId(), "ct_type_visite_id" => $type_visite_id], ["usg_trf_annee" => "DESC"]);
            $tarif = $usage_tarif->getUsgTrfPrix();
            $pvId = $ctImprimeTechRepository->findOneBy(["id" => 12]);
            $arretePvTarif = $ctVisiteExtraTarifRepository->findBy(["ct_imprime_tech_id" => $pvId->getId()], ["ct_arrete_prix_id" => "DESC"]);
            foreach($arretePvTarif as $apt){
                $arretePrix = $apt->getCtArretePrixId();
                if($liste->isVstIsContreVisite() == false){
                    //if($liste->getVstCreated() >= $arretePrix->getArtDateApplication()){
                    // secours fotsiny  new date time fa mila atao daten'ilay pv no tena izy
                    if(new \DateTime() >= $arretePrix->getArtDateApplication()){
                        if($liste->isVstIsApte() == true){
                            $prixPv = $apt->getVetPrix();
                        } else {
                            $prixPv = 2 * $apt->getVetPrix();
                        }
                        break;
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
            $html = $this->renderView('ct_app_imprimable/pv_modification_visite_contre.html.twig', [
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_MODIFICATION_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_MODIFICATION_".$id."_CONTRE_VISITE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } elseif($visite->isVstIsApte()) {
            $html = $this->renderView('ct_app_imprimable/pv_modification_visite_premiere.html.twig', [
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
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_MODIFICATION_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_MODIFICATION_".$id."_VISITE_APTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        } else {
            $html = $this->renderView('ct_app_imprimable/proces_verbal_visite_inapte.html.twig', [
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
                'visite' => $visite,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();
            $filename = "PROCES_VERBAL_MODIFICATION_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
            file_put_contents($dossier.$filename, $output);
            $dompdf->stream("PROCES_VERBAL_MODIFICATION_".$id."_VISITE_INAPTE_".$carte_grise->getCgImmatriculation()."_".$type_visite."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
                "Attachment" => true,
            ]);
        }
    }

    /**
     * @Route("/caracteristique/{id}", name="app_ct_app_imprimable_caracteristique", methods={"GET", "POST"})
     */
    public function RenseignementVehicule(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository)//: Response
    {
        $carte_grise = $ctCarteGriseRepository->findOneBy(["id" => $id]);
        $vehicule = $carte_grise->getCtVehiculeId();

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');
        $dossier = $this->getParameter('dossier_caracteristique')."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }

        $html = $this->renderView('ct_app_imprimable/caracteristique_vehicule.html.twig', [
            'logo' => $logo,
            'date' => $date,
            'centre' => $this->getUser()->getCtCentreId()->getCtrNom(),
            'province' => $this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvNom(),
            'ct_carte_grise' => $carte_grise,
            'vehicule_identification' => $vehicule,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "CARACTERISTIQUE_VEHICULE_".$id."_".$carte_grise->getCgImmatriculation()."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("CARACTERISTIQUE_VEHICULE_".$id."_".$carte_grise->getCgImmatriculation()."_".$this->getUser()->getCtCentreId()->getCtrNom().'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/feuille_utilsation", name="app_ct_app_imprimable_feuille_utilisation", methods={"GET", "POST"})
     */
    public function FeuilleUtilisation(Request $request, CtAutreVenteRepository $ctAutreVenteRepository, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechinqueRepository, CtVisiteRepository $ctVisiteRepository, CtReceptionRepository $ctReceptionRepository, CtConstAvDedRepository $ctConstAvDedRepository, CtCentreRepository $ctCentreRepository , CtImprimeTechUseRepository $ctImprimeTechUseRepository, string $numero)//: Response
    {
        //$type_reception = "";
        $date_utilisation = new \DateTime();
        $date_of_utilisation = new \DateTime();
        //$type_reception_id = new CtTypeReception();
        $centre = new CtCentre();
        $liste_utiliser = new ArrayCollection();
        if($request->request->get('form')){
            $rechercheform = $request->request->get('form');
            //$recherche = $rechercheform['ct_type_reception_id'];
            $date_utilisation = $rechercheform['date'];
            $date_of_utilisation = new \DateTime($date_utilisation);
            //$type_reception_id = $ctTypeReceptionRepository->findOneBy(["id" => $recherche]);
            //$type_reception = $type_reception_id->getTprcpLibelle();
            if($rechercheform['ct_centre_id'] != ""){
                $centre = $ctCentreRepository->findOneBy(["id" => $rechercheform['ct_centre_id']]);
            } else{
                $centre = $this->getUser()->getCtCentreId();
            }
        }
        $imprime_technique_utiliser = $ctImprimeTechUseRepository->findByUtilisation($centre, $date_of_utilisation);
        $numero = 0;
        $immatriculation = "";
        foreach($imprime_technique_utiliser as $itu){
            $nombre = 0;
            $utiliser_1=[
                "numero" => "-",
                "reference_operation" => "-",
                "immatriculation" => "-",
                "motif" => "-",
                "pvo" => "-",
                "pvm" => "-",
                "pvmc" => "-",
                "pvmr" => "-",
                "ce" => "-",
                "cb" => "-",
                "cj" => "-",
                "cjbr" => "-",
                "cr" => "-",
                "cae" => "-",
                "cim_31" => "-",
                "cim_31_bis" => "-",
                "cim_32" => "-",
                "cim_32_bis" => "-",
                "plaque_chassis" => "-",
                "adm" => "",
                "observation" => "-",
            ];
            $liste_controle = $ctImprimeTechUseRepository->findByUtilisationControle($centre, $date_of_utilisation, $itu->getCtControleId());
            foreach($liste_controle as $lst_ctrl){
                $reference_operation = "-";
                switch($lst_ctrl->getCtUsageItId->get){
                    case 10:
                        $visite = $ctVisiteRepository->findOneBy(["id" => $lst_ctrl->getCtControleId()]);
                        $reference_operation = $visite->getVstNumPv();
                        break;
                    case 11:
                        $reception = $ctReceptionRepository->findOneBy(["id" => $lst_ctrl->getCtControleId()]);
                        $reference_operation = $reception->getRcpNumPv();
                        break;
                    case 12:
                        $constatation = $ctConstAvDedRepository->findOneBy(["id" => $lst_ctrl->getCtControleId()]);
                        $reference_operation = $constatation->getCadNumero();
                        break;
                    default:
                        $autre_service = $ctAutreVenteRepository->findOneBy(["id" => $lst_ctrl->getCtControleId()]);
                        $type = $autre_service;
                        $reference_operation = $autre_service->getCadNumero();
                        break;
                }
                    $it = $lst_ctrl->getCtImprimeTechId()->getId();
                    $utiliser_1=[
                        "numero" => ++$numero,
                        "reference_operation" => $reference_operation,
                        "immatriculation" => "-",
                        "motif" => "-",
                        "pvo" => $it == 12 ? $lst_ctrl->getItuNumero() : "-",
                        "pvm" => $it == 13 ? $lst_ctrl->getItuNumero() : "-",
                        "pvmc" => $it == 14 ? $lst_ctrl->getItuNumero() : "-",
                        "pvmr" => $it == 15 ? $lst_ctrl->getItuNumero() : "-",
                        "ce" => $it == 1 ? $lst_ctrl->getItuNumero() : "-",
                        "cb" => $it == 2 ? $lst_ctrl->getItuNumero() : "-",
                        "cj" => $it == 4 ? $lst_ctrl->getItuNumero() : "-",
                        "cjbr" => $it == 5 ? $lst_ctrl->getItuNumero() : "-",
                        "cr" => $it == 6 ? $lst_ctrl->getItuNumero() : "-",
                        "cae" => $it == 7 ? $lst_ctrl->getItuNumero() : "-",
                        "cim_31" => $it == 9 ? $lst_ctrl->getItuNumero() : "-",
                        "cim_31_bis" => $it == 10 ? $lst_ctrl->getItuNumero() : "-",
                        "cim_32" => $it == 11 ? $lst_ctrl->getItuNumero() : "-",
                        "cim_32_bis" => $it == 3 ? $lst_ctrl->getItuNumero() : "-",
                        "plaque_chassis" => $it == 8 ? $lst_ctrl->getItuNumero() : "-",
                        "adm" => "-",
                        "observation" => "-",
                    ];
            }

            $utiliser_precedent = $utiliser;
            $liste_utiliser->add($utiliser);
        }

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');
        $dossier = $this->getParameter('dossier_feuille_utilisation_imprime_technique')."/".$this->getUser()->getCtCentreId()->getCtrNom().'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }



        $html = $this->renderView('ct_app_imprimable/bordereau_envoi.html.twig', [
            'logo' => $logo,
            'date' => $date,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "BORDEREAU_ENVOI".'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("BORDEREAU_ENVOI".'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }

    /**
     * @Route("/bordereau_envoi/{numero}", name="app_ct_app_imprimable_bordereau_envoi", methods={"GET", "POST"})
     */
    public function BordereauEnvoi(Request $request, string $numero)//: Response
    {
        // $visite->setVstGenere($visite->getVstGenere() + 1);
        // $ctVisiteRepository->add($visite, true);

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime();
        $logo = file_get_contents($this->getParameter('logo').'logo.txt');
        $dossier = $this->getParameter('dossier_bordereau_envoi').'/'.$date->format('Y').'/'.$date->format('M').'/'.$date->format('d').'/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }

        $html = $this->renderView('ct_app_imprimable/bordereau_envoi.html.twig', [
            'logo' => $logo,
            'date' => $date,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $filename = "BORDEREAU_ENVOI".'_'.$date->format('Y_M_d_H_i_s').".pdf";
        file_put_contents($dossier.$filename, $output);
        $dompdf->stream("BORDEREAU_ENVOI".'_'.$date->format('Y_M_d_H_i_s').".pdf", [
            "Attachment" => true,
        ]);
    }
}