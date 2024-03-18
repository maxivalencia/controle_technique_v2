<?php

namespace App\Controller;

use App\Entity\CtTypeVisite;
use App\Entity\CtVisite;
use App\Entity\CtCarteGrise;
use App\Entity\CtCentre;
use App\Entity\CtVehicule;
use App\Entity\CtAnomalie;
use App\Entity\CtUsage;
use App\Entity\CtVisiteExtra;
use App\Form\CtTypeVisiteType;
use App\Entity\CtUser;
use App\Entity\CtUtilisation;
use App\Form\CtCarteGriseType;
use App\Form\CtAnomalieType;
use App\Entity\CtImprimeTechUse;
use App\Entity\CtAutreVente;
use App\Form\CtCarteGriseType as FormCtCarteGriseType;
use App\Repository\CtTypeVisiteRepository;
use App\Repository\CtVisiteExtraRepository;
use App\Repository\CtCarteGriseRepository;
use App\Repository\CtVehiculeRepository;
use App\Repository\CtAutreTarifRepository;
use App\Repository\CtAutreVenteRepository;
use App\Repository\CtUsageImprimeTechniqueRepository;
use App\Repository\CtUserRepository;
use App\Form\CtRensCarteGriseType;
use App\Form\CtVisiteCarteGriseType;
use App\Form\CtRensVehiculeType;
use App\Form\CtVehiculeType;
use App\Form\CtVisiteVehiculeType;
use App\Form\CtVisiteVisiteType;
use App\Form\CtVisiteVisiteDisableType;
use App\Form\CtVisiteVisiteDuplicataType;
use App\Form\CtVisiteType;
use App\Form\CtAutreVenteType;
use App\Form\CtAutreVenteAutreVenteType;
use App\Form\CtImprimeTechUseType;
use App\Form\CtImprimeTechUseModulableType;
use App\Form\CtAutreVenteAuthenticiteType;
use App\Form\CtAutreVenteVisiteSpecialType;
use App\Entity\CtImprimeTech;
use App\Entity\CtHistorique;
use App\Entity\CtHistoriqueType;
//use App\Form\CtHistoriqueType;
use App\Form\CtHistoriqueTypeType;
use App\Repository\CtHistoriqueRepository;
use App\Repository\CtHistoriqueTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\DataTransformer\IssueToNumberTransformer;
use App\Repository\CtUtilisationRepository;
use App\Repository\CtVisiteRepository;
use App\Repository\CtImprimeTechUseRepository;
use App\Repository\CtImprimeTechRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * @Route("/ct_app_visite")
 */
class CtAppVisiteController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_visite")
     */
    public function index(): Response
    {
        return $this->render('ct_app_visite/index.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/liste_type", name="app_ct_app_visite_liste_type", methods={"GET"})
     */
    public function ListeType(CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        return $this->render('ct_app_visite/liste_type.html.twig', [
            'ct_type_visites' => $ctTypeVisiteRepository->findAll(),
            'total' => count($ctTypeVisiteRepository->findAll()),
        ]);
    }

    /**
     * @Route("/creer_type", name="app_ct_app_visite_creer_type", methods={"GET", "POST"})
     */
    public function CreerType(Request $request, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        $ctTypeVisite = new CtTypeVisite();
        $form = $this->createForm(CtTypeVisiteType::class, $ctTypeVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeVisiteRepository->add($ctTypeVisite, true);

            return $this->redirectToRoute('app_ct_app_visite_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_visite/creer_type.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/voir_type/{id}", name="app_ct_app_visite_voir_type", methods={"GET"})
     */
    public function VoirType(CtTypeVisite $ctTypeVisite): Response
    {
        return $this->render('ct_app_visite/voir_type.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
        ]);
    }

    /**
     * @Route("/edit_type/{id}", name="app_ct_app_visite_edit_type", methods={"GET", "POST"})
     */
    public function EditType(Request $request, CtTypeVisite $ctTypeVisite, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        $form = $this->createForm(CtTypeVisiteType::class, $ctTypeVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeVisiteRepository->add($ctTypeVisite, true);

            return $this->redirectToRoute('app_ct_app_visite_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_visite/edit_type.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/del_type/{id}", name="app_ct_app_visite_del_type", methods={"GET", "POST"})
     */
    public function delete(Request $request, CtTypeVisite $ctTypeVisite, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        $ctTypeVisiteRepository->remove($ctTypeVisite, true);

        return $this->redirectToRoute('app_ct_app_visite_liste_type', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/renseignement_vehicule", name="app_ct_app_visite_renseignement_vehicule", methods={"GET", "POST"})
     */
    public function RenseignementVehicule(Request $request, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        $ctCarteGrise_new = new CtCarteGrise();
        $ctVehicule = new CtVehicule();
        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->request->get('search-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('search-numero-serie')){
            $recherche = $request->query->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('search-identification')){
            $recherche = $request->query->get('search-identification');
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }

        if($ctCarteGrise == null){
            $ctCarteGrise = new CtCarteGrise();
        }
        if($ctCarteGrise->getId() == null){
            $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise);
        }else{
            $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise, ["disable" => true]);
        }
        $form_carte_grise->handleRequest($request);
        $message = "";
        $enregistrement_ok = False;

        if ($form_carte_grise->isSubmitted() && $form_carte_grise->isValid()) {
            try{
                $ctVehicule->setCtGenreId($ctCarteGrise->getCtVehiculeId()->getCtGenreId());
                $ctVehicule->setCtMarqueId($ctCarteGrise->getCtVehiculeId()->getCtMarqueId());
                $ctVehicule->setVhcCylindre($ctCarteGrise->getCtVehiculeId()->getVhcCylindre());
                $ctVehicule->setVhcPuissance($ctCarteGrise->getCtVehiculeId()->getVhcPuissance());
                $ctVehicule->setVhcPoidsVide($ctCarteGrise->getCtVehiculeId()->getVhcPoidsVide());
                $ctVehicule->setVhcChargeUtile($ctCarteGrise->getCtVehiculeId()->getVhcChargeUtile());
                $ctVehicule->setVhcNumSerie($ctCarteGrise->getCtVehiculeId()->getVhcNumSerie());
                $ctVehicule->setVhcNumMoteur($ctCarteGrise->getCtVehiculeId()->getVhcNumMoteur());
                $ctVehicule->setVhcCreated(new \DateTime());
                $ctVehicule->setVhcType($ctCarteGrise->getCtVehiculeId()->getVhcType());
                $ctVehicule->setVhcPoidsTotalCharge($ctCarteGrise->getCtVehiculeId()->getVhcPoidsVide() + $ctCarteGrise->getCtVehiculeId()->getVhcChargeUtile());

                $ctVehiculeRepository->add($ctVehicule, true);

                $ctCarteGrise_new->setCtCarrosserieId($ctCarteGrise->getCtCarrosserieId());
                $ctCarteGrise_new->setCtCentreId($this->getUser()->getCtCentreId());
                $ctCarteGrise_new->setCtSourceEnergieId($ctCarteGrise->getCtSourceEnergieId());
                $ctCarteGrise_new->setCtUserId($this->getUser());
                $ctCarteGrise_new->setCgDateEmission($ctCarteGrise->getCgDateEmission());
                $ctCarteGrise_new->setCgNom($ctCarteGrise->getCgNom());
                $ctCarteGrise_new->setCgPrenom($ctCarteGrise->getCgPrenom());
                $ctCarteGrise_new->setCgProfession($ctCarteGrise->getCgProfession());
                $ctCarteGrise_new->setCgAdresse($ctCarteGrise->getCgAdresse());
                $ctCarteGrise_new->setCgPhone($ctCarteGrise->getCgPhone());
                $ctCarteGrise_new->setCgNbrAssis($ctCarteGrise->getCgNbrAssis());
                $ctCarteGrise_new->setCgNbrDebout(0);
                $ctCarteGrise_new->setCgPuissanceAdmin($ctCarteGrise->getCtVehiculeId()->getVhcPuissance());
                $ctCarteGrise_new->setCgMiseEnService($ctCarteGrise->getCgMiseEnService());
                $ctCarteGrise_new->setCgPatente($ctCarteGrise->getCgPatente());
                $ctCarteGrise_new->setCgAni($ctCarteGrise->getCgAni());
                $ctCarteGrise_new->setCgRta($ctCarteGrise->getCgRta());
                $ctCarteGrise_new->setCgNumCarteViolette($ctCarteGrise->getCgNumCarteViolette());
                $ctCarteGrise_new->setCgDateCarteViolette($ctCarteGrise->getCgDateCarteViolette());
                $ctCarteGrise_new->setCgLieuCarteViolette($ctCarteGrise->getCgLieuCarteViolette());
                $ctCarteGrise_new->setCgNumVignette($ctCarteGrise->getCgNumVignette());
                $ctCarteGrise_new->setCgDateVignette($ctCarteGrise->getCgDateVignette());
                $ctCarteGrise_new->setCgLieuVignette($ctCarteGrise->getCgLieuVignette());
                $ctCarteGrise_new->setCgImmatriculation($ctCarteGrise->getCgImmatriculation());
                $ctCarteGrise_new->setCgCreated(new \DateTime());
                $ctCarteGrise_new->setCgNomCooperative($ctCarteGrise->getCgNomCooperative());
                $ctCarteGrise_new->setCgItineraire($ctCarteGrise->getCgItineraire());
                $ctCarteGrise_new->setCgIsTransport($ctCarteGrise->isCgIsTransport());
                $ctCarteGrise_new->setCgNumIdentification($ctCarteGrise->getCgNumIdentification());
                $ctCarteGrise_new->setCtZoneDesserteId($ctCarteGrise->getCtZoneDesserteId());
                $ctCarteGrise_new->setCgIsActive(true);
                $ctCarteGrise_new->setCgAntecedantId($ctCarteGrise->getId());
                $ctCarteGrise_new->setCgObservation($ctCarteGrise->getCgObservation());
                $ctCarteGrise_new->setCgCommune($ctCarteGrise->getCgCommune());
                $ctCarteGrise_new->setCtVehiculeId($ctVehicule);
                $ctCarteGrise_new->setCtUtilisationId($ctCarteGrise->getCtUtilisationId());

                $ctCarteGriseRepository->add($ctCarteGrise_new, true);

                if($ctCarteGrise->getId() != null && $ctCarteGrise->getId() < $ctCarteGrise_new->getId()){
                    $ctCarteGrise->setCgIsActive(false);

                    $ctCarteGriseRepository->add($ctCarteGrise, true);
                }

                $message = "Enregistrement effectué avec succès du véhicule portant l'immatriculation : ".$ctCarteGrise->getCgImmatriculation();
                $enregistrement_ok = True;
            } catch (Exception $e) {
                $message = "Echec de l'enregistrement du véhicule";
                $enregistrement_ok = False;
            }
        }
        return $this->render('ct_app_visite/renseignement_vehicule.html.twig', [
            'ct_carte_grise' => $ctCarteGrise,
            'form_carte_grise' => $form_carte_grise->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
        ]);
    }

    /**
     * @Route("/mutation_vehicule", name="app_ct_app_visite_mutation_vehicule", methods={"GET", "POST"})
     */
    public function MutationVehicule(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        $ctCarteGrise_new = new CtCarteGrise();
        $ctVehicule = new CtVehicule();
        $immatriculation = '';
        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->request->get('search-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->query->get('search-numero-serie')){
            $recherche = $request->query->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->query->get('search-identification')){
            $recherche = $request->query->get('search-identification');
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }

        if($ctCarteGrise == null){
        //if(is_null($ctCarteGrise)){
            $ctCarteGrise = new CtCarteGrise();
        }
        if($ctCarteGrise->getId() == null){
        //if(is_null($ctCarteGrise)){
            $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise, ["disable" => true]);
        }else{
            $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise, ["disable" => false]);
            /* if($ctCarteGrise->getId() == null){
                $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise, ["disable" => true]);
            }else{
                $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise, ["disable" => false]);
            } */
        }
        $form_carte_grise->handleRequest($request);
        $message = "";
        $enregistrement_ok = False;
        //var_dump($ctCarteGrise);

        if ($form_carte_grise->isSubmitted() && $form_carte_grise->isValid()) {
            try{
                //var_dump($ctCarteGrise->getCgImmatriculation());
                $imm = "";
                $imm = strtoupper($request->request->get('immatriculation'));
                if($imm == ""){
                    $imm = strtoupper($request->query->get('immatriculation'));
                }
                $ctCarteGrise_old = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $imm], ["id" => "DESC"]);
                //var_dump($ctCarteGrise_old);
                
                $ctVehicule->setCtGenreId($ctCarteGrise->getCtVehiculeId()->getCtGenreId());
                $ctVehicule->setCtMarqueId($ctCarteGrise->getCtVehiculeId()->getCtMarqueId());
                $ctVehicule->setVhcCylindre($ctCarteGrise->getCtVehiculeId()->getVhcCylindre());
                $ctVehicule->setVhcPuissance($ctCarteGrise->getCtVehiculeId()->getVhcPuissance());
                $ctVehicule->setVhcPoidsVide($ctCarteGrise->getCtVehiculeId()->getVhcPoidsVide());
                $ctVehicule->setVhcChargeUtile($ctCarteGrise->getCtVehiculeId()->getVhcChargeUtile());
                $ctVehicule->setVhcNumSerie($ctCarteGrise->getCtVehiculeId()->getVhcNumSerie());
                $ctVehicule->setVhcNumMoteur($ctCarteGrise->getCtVehiculeId()->getVhcNumMoteur());
                $ctVehicule->setVhcCreated(new \DateTime());
                $ctVehicule->setVhcType($ctCarteGrise->getCtVehiculeId()->getVhcType());
                $ctVehicule->setVhcPoidsTotalCharge($ctCarteGrise->getCtVehiculeId()->getVhcPoidsVide() + $ctCarteGrise->getCtVehiculeId()->getVhcChargeUtile());

                $ctVehiculeRepository->add($ctVehicule, true);

                $ctCarteGrise_new->setCtCarrosserieId($ctCarteGrise->getCtCarrosserieId());
                $ctCarteGrise_new->setCtCentreId($this->getUser()->getCtCentreId());
                $ctCarteGrise_new->setCtSourceEnergieId($ctCarteGrise->getCtSourceEnergieId());
                $ctCarteGrise_new->setCtUserId($this->getUser());
                $ctCarteGrise_new->setCgDateEmission($ctCarteGrise->getCgDateEmission());
                $ctCarteGrise_new->setCgNom($ctCarteGrise->getCgNom());
                $ctCarteGrise_new->setCgPrenom($ctCarteGrise->getCgPrenom());
                $ctCarteGrise_new->setCgProfession($ctCarteGrise->getCgProfession());
                $ctCarteGrise_new->setCgAdresse($ctCarteGrise->getCgAdresse());
                $ctCarteGrise_new->setCgPhone($ctCarteGrise->getCgPhone());
                $ctCarteGrise_new->setCgNbrAssis($ctCarteGrise->getCgNbrAssis());
                $ctCarteGrise_new->setCgNbrDebout(0);
                $ctCarteGrise_new->setCgPuissanceAdmin($ctCarteGrise->getCtVehiculeId()->getVhcPuissance());
                $ctCarteGrise_new->setCgMiseEnService($ctCarteGrise->getCgMiseEnService());
                $ctCarteGrise_new->setCgPatente($ctCarteGrise->getCgPatente());
                $ctCarteGrise_new->setCgAni($ctCarteGrise->getCgAni());
                $ctCarteGrise_new->setCgRta($ctCarteGrise->getCgRta());
                $ctCarteGrise_new->setCgNumCarteViolette($ctCarteGrise->getCgNumCarteViolette());
                $ctCarteGrise_new->setCgDateCarteViolette($ctCarteGrise->getCgDateCarteViolette());
                $ctCarteGrise_new->setCgLieuCarteViolette($ctCarteGrise->getCgLieuCarteViolette());
                $ctCarteGrise_new->setCgNumVignette($ctCarteGrise->getCgNumVignette());
                $ctCarteGrise_new->setCgDateVignette($ctCarteGrise->getCgDateVignette());
                $ctCarteGrise_new->setCgLieuVignette($ctCarteGrise->getCgLieuVignette());
                $ctCarteGrise_new->setCgImmatriculation($ctCarteGrise->getCgImmatriculation());
                $ctCarteGrise_new->setCgCreated(new \DateTime());
                $ctCarteGrise_new->setCgNomCooperative($ctCarteGrise->getCgNomCooperative());
                $ctCarteGrise_new->setCgItineraire($ctCarteGrise->getCgItineraire());
                $ctCarteGrise_new->setCgIsTransport($ctCarteGrise->isCgIsTransport());
                $ctCarteGrise_new->setCgNumIdentification($ctCarteGrise->getCgNumIdentification());
                $ctCarteGrise_new->setCtZoneDesserteId($ctCarteGrise->getCtZoneDesserteId());
                $ctCarteGrise_new->setCgIsActive(true);
                $ctCarteGrise_new->setCgAntecedantId($ctCarteGrise_old);
                $ctCarteGrise_new->setCgObservation($ctCarteGrise->getCgObservation());
                $ctCarteGrise_new->setCgCommune($ctCarteGrise->getCgCommune());
                $ctCarteGrise_new->setCtVehiculeId($ctVehicule);
                $ctCarteGrise_new->setCtUtilisationId($ctCarteGrise->getCtUtilisationId());

                $ctCarteGriseRepository->add($ctCarteGrise_new, true);

                //if($ctCarteGrise->getId() != null && $ctCarteGrise->getId() < $ctCarteGrise_new->getId()){
                $ctCarteGrise_old->setCgIsActive(false);
                $ctCarteGriseRepository->add($ctCarteGrise_old, true);
                //}

                $message = "Enregistrement effectué avec succès du véhicule portant l'immatriculation : ".$ctCarteGrise->getCgImmatriculation();
                $enregistrement_ok = False;
                $date_mutation = new \DateTime();
                $ct_visite_old = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise_old->getId()], ["id" => "DESC"]);
                //if(!is_null($ct_visite_old)){
                    $ct_visite_new = clone($ct_visite_old);
                    $ct_visite_new->setCtCarteGriseId($ctCarteGrise_new);
                    $ct_visite_new->setVstObservation($ct_visite_new->getVstObservation()." Mutation du visite portant numero pv : ".$ct_visite_old->getId().", date mutation :".$date_mutation->format("d/m/Y").", par le secrétaire ".$this->getUser()->getId()." ".$this->getUser());
                    $ct_visite_old->setVstIsActive(true);
                    $ct_visite_old->setVstIsActive(false);
                    $ct_visite_old->setVstUpdated(new \DateTime());
                    $ctVisiteRepository->add($ct_visite_new, true);
                    $ctVisiteRepository->add($ct_visite_old, true);
                    // assiana redirection mandeha amin'ny générer mutation rehefa vita ilay mutation fa ito mbola essaye fotsiny
                    return $this->redirectToRoute('app_ct_app_visite_recapitulation_mutation', ["id" => $ct_visite_new->getId(), "old" => $ctCarteGrise_old->getId()], Response::HTTP_SEE_OTHER);
                //}
            } catch (Exception $e) {
                $message = "Echec de l'enregistrement du véhicule";
                $enregistrement_ok = False;
            }
        }
        return $this->render('ct_app_visite/mutation_vehicule.html.twig', [
            'ct_carte_grise' => $ctCarteGrise,
            'form_carte_grise' => $form_carte_grise->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            'immatriculation' => $immatriculation,
        ]);
    }

    /**
     * @Route("/recherche_vehicule", name="app_ct_app_visite_recherche_vehicule", methods={"GET", "POST"})
     */
    public function RechercheVehicule(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        $ctCarteGrise_new = new CtCarteGrise();
        $ctVehicule = new CtVehicule();
        $immatriculation = '';
        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->request->get('search-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->query->get('search-numero-serie')){
            $recherche = $request->query->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        if($request->query->get('search-identification')){
            $recherche = $request->query->get('search-identification');
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }

        if($ctCarteGrise == null){
            $ctCarteGrise = new CtCarteGrise();
        }
        $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise, ["disable" => true]);
        $form_carte_grise->handleRequest($request);
        $message = "";
        $enregistrement_ok = False;
        //var_dump($ctCarteGrise);

        if ($form_carte_grise->isSubmitted() && $form_carte_grise->isValid()) {
            try{
                //var_dump($ctCarteGrise->getCgImmatriculation());
                $imm = "";
                $imm = strtoupper($request->request->get('immatriculation'));
                if($imm == ""){
                    $imm = strtoupper($request->query->get('immatriculation'));
                }
                $immatriculation = $imm;
            } catch (Exception $e) {
                $message = "Echec de l'enregistrement du véhicule";
                $enregistrement_ok = False;
            }
        }
        return $this->render('ct_app_visite/recherche_vehicule.html.twig', [
            'ct_carte_grise' => $ctCarteGrise,
            'form_carte_grise' => $form_carte_grise->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            'immatriculation' => $immatriculation,
        ]);
    }

    /**
     * @Route("/creer_visite", name="app_ct_app_visite_creer_visite", methods={"GET", "POST"})
     */
    public function CreerVisite(Request $request, CtImprimeTechUseRepository $ctImprimeTechUseRepository, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctVisite = new CtVisite();
        $ctVisite_new = new CtVisite();
        $ctCarteGrise = new CtCarteGrise();
        $message = "";
        $enregistrement_ok = False;
        $immatriculation = "";
        $centre = $this->getUser()->getCtCentreId();
        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
        }
        if($request->request->get('search-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('search-numero-serie')){
            $recherche = strtoupper($request->query->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
        }
        if($request->query->get('search-identification')){
            $recherche = strtoupper($request->query->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }

        if($ctCarteGrise != null){
            $ctVisite->setCtCarteGriseId($ctCarteGrise);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        $ctVisite->setCtCentreId($this->getUser()->getCtCentreId());

        $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $immatriculation, "centre" => $centre]);
        //$form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $immatriculation]);
        $form_visite->handleRequest($request);
        // eto mbola mila manao liste misy création des vérificateur izay vao ampidirina ao anatin'ilay form fiche vérificateur
        $form_feuille_de_caisse = $this->createFormBuilder()
            ->add('date', DateType::class, [
                'label' => 'Séléctionner la date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                    'style' => 'width:100%;',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_type_visite_id', EntityType::class, [
                'label' => 'Séléctionner le type de visite',
                'class' => CtTypeVisite::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Séléctionner le centre',
                'class' => CtCentre::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->getForm();

        $form_fiche_verificateur = $this->createFormBuilder()
            ->add('date', DateType::class, [
                'label' => 'Séléctionner la date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                    'style' => 'width:100%;',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Séléctionner le centre',
                'class' => CtCentre::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'multiple' => false,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('ct_user_id', EntityType::class, [
                'label' => 'Séléctionner verificateur',
                'class' => CtUser::class,
                'query_builder' => function(CtUserRepository $ctUserRepository){
                    $qb = $ctUserRepository->createQueryBuilder('u');
                    return $qb
                        ->Where('u.ct_role_id = :val1')
                        ->andWhere('u.ct_centre_id = :val2')
                        ->setParameter('val1', 3)
                        ->setParameter('val2', $this->getUser()->getCtCentreId())
                    ;
                },
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->getForm();

        $form_liste_anomalies = $this->createFormBuilder()
            ->add('date', DateType::class, [
                'label' => 'Séléctionner la date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                    'style' => 'width:100%;',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Séléctionner le centre',
                'class' => CtCentre::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'multiple' => false,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->getForm();
        $form_feuille_de_caisse->handleRequest($request);
        $form_fiche_verificateur->handleRequest($request);
        $form_liste_anomalies->handleRequest($request);
        /* if($request->request->get('immatriculation')){
            $immatriculation = strtoupper($request->request->get('immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
        } */

        /* $form_imprime_technique = $this->createFormBuilder()
            ->add('ct_imprime_tech_use_id', EntityType::class, [
                'label' => 'Imprimé technique',
                'class' => CtImprimeTechUse::class,
                'query_builder' => function(CtImprimeTechUseRepository $ctImprimeTechUseRepository){
                    $qb = $ctImprimeTechUseRepository->createQueryBuilder('u');
                    $centre = $this->getUser()->getCtCentreId();
                    return $qb
                        ->Where('u.itu_used = :val1')
                        ->andWhere('u.ct_centre_id = :val2')
                        ->setParameter('val1', 0)
                        ->setParameter('val2', $centre)
                    ;
                },
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => false,
                ],
            ])
            ->getForm();
        $form_imprime_technique->handleRequest($request); */

        if ($form_visite->isSubmitted() && $form_visite->isValid()) {
            $ctVisite_new = $ctVisite;
            if($request->request->get('ct_visite_visite')){
                //$recherche = $request->request->get('ct_visite_visite');
                $recherche = $request->request->get('ct_visite_visite');
                $rech = strtoupper($recherche['vst_observation']);
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $rech, "cg_is_active" => 1], ["id" => "DESC"]);

            }
            //$ctVisite_new->setCtCarteGriseId($ctCarteGrise->getId());
            $ctVisite_new->setCtCarteGriseId($ctCarteGrise);
            $ctVisite_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctVisite_new->setCtTypeVisiteId($ctVisite->getCtTypeVisiteId());
            $ctVisite_new->setCtUsageId($ctVisite->getCtUsageId());
            $ctVisite_new->setCtUserId($this->getUser());
            $ctVisite_new->setCtVerificateurId($ctVisite->getCtVerificateurId());
            //$ctVisite_new->setVstNumPv($ctVisite_new->getId().'/'.$ctVisite_new->getCtCentreId()->getCtProvinceId()->getPrvNom().'/'.$ctVisite_new->getCtTypeVisiteId().'/'.date("Y"));
            $date_expiration = new \DateTime();
            $date_expiration->modify('+'.$ctVisite->getCtUsageId()->getUsgValidite().' month');
            //$date_expiration = $date_expiration->format('Y-m-d');
            $ctVisite_new->setVstDateExpiration($date_expiration);
            $date = new \DateTime();
            $ctVisite_new->setVstNumFeuilleCaisse($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisite_new->setVstCreated(new \DateTime());
            $ctVisite_new->setVstUpdated($ctVisite->getVstUpdated());
            $ctVisite_new->setCtUtilisationId($ctCarteGrise->getCtUtilisationId());
            $anml = $ctVisite_new->getVstAnomalieId();
            $ctVisite_new->setVstIsApte($anml->count()>0?false:true);
            $ctVisite_new->setVstIsContreVisite(false);
            $ctVisite_new->setVstDureeReparation($ctVisite->getVstDureeReparation());
            $liste_extra = $ctVisite->getCtExtraVentes();
            //var_dump($ctVisite->getCtExtraVentes());
            foreach($liste_extra as $extra){
                $ctVisite_new->addCtExtraVente($extra);
            }
            //$ctVisite_new->setVstAnomalieId($ctVisite->getVstAnomalieId());
            $liste_anomalie = $ctVisite->getVstAnomalieId();
            foreach($liste_anomalie as $anomalie){
                $ctVisite_new->addVstAnomalieId($anomalie);
            }
            $liste_imprime = $ctVisite->getVstImprimeTechId();
            foreach($liste_imprime as $imprime){
                $ctVisite_new->addVstImprimeTechId($imprime);
            }
            $ctVisite_new->setVstIsActive(true);
            $ctVisite_new->setVstGenere(0);
            $ctVisite_new->setVstObservation(" - ");

            $ctVisiteRepository->add($ctVisite_new, true);
            $ctVisite_new->setVstNumPv($ctVisite_new->getId().'/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisiteRepository->add($ctVisite_new, true);

            /* if($ctVisite->getId() != null && $ctVisite->getId() < $ctVisite_new->getId()){
                $ctVisite->setVstIsActive(false);
                $ctVisite->setVstUpdated(new \DateTime());

                $ctVisiteRepository->add($ctVisite, true);
            } */

            $message = "Visite ajouter avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            //return $this->redirectToRoute('app_ct_app_visite_recapitulation_visite', ["id" => $ctVisite_new->getId()], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_ct_app_imprime_technique_mise_a_jour_multiple', ["id" => $ctVisite_new->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ct_app_visite/creer_visite.html.twig', [
            'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
            'form_fiche_verificateur' => $form_fiche_verificateur->createView(),
            'form_liste_anomalies' => $form_liste_anomalies->createView(),
            'form_visite' => $form_visite->createView(),
            'immatriculation' => $immatriculation,
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            //'form_imprime_technique' => $form_imprime_technique->createView(),
        ]);
    }

    /**
     * @Route("/recapitulation_visite/{id}", name="app_ct_app_visite_recapitulation_visite", methods={"GET", "POST"})
     */
    public function RecapitulationVisite(Request $request, int $id, CtVisite $ctVisite, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        return $this->render('ct_app_visite/resume_visite.html.twig', [
            'ct_visite' => $ctVisite,
        ]);
    }

    /**
     * @Route("/recapitulation_mutation/{id}/{old}", name="app_ct_app_visite_recapitulation_mutation", methods={"GET", "POST"})
     */
    public function RecapitulationMutation(Request $request, int $id, int $old, CtVisiteRepository $ctVisiteRepository, CtVisite $ctVisite, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $carte_grise_old = $ctCarteGriseRepository->findOneBy(["id" => $old], ["id" => "DESC"]);
        $carte_grise_old->setCgIsActive(false);
        $ctCarteGriseRepository->add($carte_grise_old, true);
        return $this->render('ct_app_visite/resume_mutation.html.twig', [
            'ct_visite' => $ctVisite,
        ]);
    }

    /**
     * @Route("/feuille_de_caisse", name="app_ct_app_visite_feuille_de_caise", methods={"GET", "POST"})
     */
    public function FeuilleDeCaisse(Request $request): Response
    {
        // efa ao amin'ny CtAppImprimable ny manao azy
        return $this->render('ct_app_visite/creer_visite.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/fiche_veriricateur", name="app_ct_app_visite_fiche_verificateur", methods={"GET", "POST"})
     */
    public function FicheVerificateur(Request $request): Response
    {
        return $this->render('ct_app_visite/creer_visite.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/liste_anomalies", name="app_ct_app_visite_liste_anomalies", methods={"GET", "POST"})
     */
    public function ListeAnomalies(Request $request): Response
    {
        return $this->render('ct_app_visite/creer_visite.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/recherche_visite", name="app_ct_app_visite_recherche_visite", methods={"GET", "POST"})
     */
    public function RechercheVisite(Request $request): Response
    {
        // efa ok ko ito
        return $this->render('ct_app_visite/recherche_visite.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/contre_visite", name="app_ct_app_visite_contre_visite", methods={"GET", "POST"})
     */
    public function ContreVisite(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        $ctVisite = new CtVisite();
        $ctVisite_contre = new CtVisite();
        $message = "";
        $message_indisponible_contre = "Pas de contre disponible pour ce véhicule";
        $enregistrement_ok = False;
        $contre = false;
        $recherche_ok = false;
        $is_transport = false;
        $immatriculation = "";
        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $recherche_ok = true;
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            $recherche_ok = true;
        }
        if($request->request->get('ssearch-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $recherche_ok = true;
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $recherche_ok = true;
        }
        if($request->query->get('search-numero-serie')){
            $recherche = strtoupper($request->query->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            $recherche_ok = true;
        }
        if($request->query->get('ssearch-identification')){
            $recherche = strtoupper($request->query->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            $recherche_ok = true;
        }

        if($ctCarteGrise != null){
            $ctVisite_old = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise], ["id" => "DESC"]);
            //if($ctVisite_old != null && $ctVisite_old->isVstIsActive() == true && $ctVisite_old->isVstIsContreVisite() == false){
            //var_dump($ctCarteGrise);
            if($ctVisite_old != null && $ctVisite_old->isVstIsContreVisite() == false){
                $date = $ctVisite_old->getVstCreated();
                $date->modify('+2 month');
                //$date = $date->format('Y-m-d H:i:s');
                if($ctVisite_old->getVstCreated() <= $date){
                    $ctVisite = $ctVisite_old;
                    $contre = true;
                }else {
                    $contre = false;
                }
            }
            $is_transport = $ctCarteGrise->isCgIsTransport();
            $ctVisite->setCtCarteGriseId($ctCarteGrise);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        $centre =  $this->getUser()->getCtCentreId();
        $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $immatriculation, "centre" => $centre]);
        $form_visite->handleRequest($request);

        if ($form_visite->isSubmitted() && $form_visite->isValid()) {
            //$ctVisite_new = $ctVisite;
            if($request->request->get('ct_visite_visite')){
                //$recherche = $request->request->get('ct_visite_visite');
                $recherche = $request->request->get('ct_visite_visite');
                $rech = strtoupper($recherche['vst_observation']);
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $rech, "cg_is_active" => 1], ["id" => "DESC"]);

            }
            //$ctVisite_contre = $ctVisite;

            $ctVisite_contre->setCtCarteGriseId($ctCarteGrise);
            $ctVisite_contre->setCtCentreId($this->getUser()->getCtCentreId());
            $ctVisite_contre->setCtTypeVisiteId($ctVisite->getCtTypeVisiteId());
            $ctVisite_contre->setCtUsageId($ctVisite->getCtUsageId());
            $ctVisite_contre->setCtUserId($this->getUser());
            $ctVisite_contre->setCtVerificateurId($ctVisite->getCtVerificateurId());
            //$ctVisite_contre->setVstNumPv($ctVisite_contre->getId().'/'.$ctVisite_contre->getCtCentreId()->getCtProvinceId()->getPrvNom().'/'.$ctVisite_contre->getCtTypeVisiteId().'/'.date("Y"));
            $date_expiration = new \DateTime();
            $date_expiration->modify('+'.$ctVisite->getCtUsageId()->getUsgValidite().' month');
            //$date_expiration = $date_expiration->format('Y-m-d');
            //$ctVisite_contre->setVstDateExpiration($date_expiration);
            $ctVisite_contre->setVstDateExpiration($ctVisite->getVstDateExpiration());
            $date = new \DateTime();
            $ctVisite_contre->setVstNumFeuilleCaisse($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisite_contre->setVstCreated(new \DateTime());
            $ctVisite_contre->setVstUpdated($ctVisite->getVstUpdated());
            $ctVisite_contre->setCtUtilisationId($ctCarteGrise->getCtUtilisationId());
            $anml = $ctVisite->getVstAnomalieId();
            //$ctVisite_contre->setVstAnomalieId($ctVisite->getVstAnomalieId());
            $liste_anomalie =$ctVisite->getVstAnomalieId();
            foreach($liste_anomalie as $anomalie){
                $ctVisite_contre->addVstAnomalieId($anomalie);
            }
            $ctVisite_contre->setVstIsApte($anml->count()>0?false:true);
            $ctVisite_contre->setVstIsContreVisite(true);
            $ctVisite_contre->setVstDureeReparation($ctVisite->getVstDureeReparation());
            $ctVisite_contre->setVstIsActive(true);
            $ctVisite_contre->setVstGenere(0);
            $ctVisite_contre->setVstObservation($ctVisite->getVstObservation()." CONTRE DU ID : ".$ctVisite->getId());

            $ctVisiteRepository->add($ctVisite_contre, true);
            $ctVisite_contre->setVstNumPv($ctVisite_contre->getId().'/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));        
            $ctVisiteRepository->add($ctVisite_contre, true);

            /* if($ctVisite->getId() != null && $ctVisite->getId() < $ctVisite_contre->getId()){
                $ctVisite->setVstIsActive(false);
                $ctVisite->setVstUpdated(new \DateTime());

                $ctVisiteRepository->add($ctVisite, true);
            } */
            $message = "Contre ajouter avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            //return $this->redirectToRoute('app_ct_app_visite_recapitulation_visite_contre', ["id" => $ctVisite_contre->getId(), "old" => $ctVisite->getId()], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_ct_app_imprime_technique_mise_a_jour_multiple', ["id" => $ctVisite_contre->getId(), "old" => $ctVisite->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ct_app_visite/contre_visite.html.twig', [
            'form_visite' => $form_visite->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            'contre' => $contre,
            'recherche_ok' => $recherche_ok,
            'message_indisponible_contre' => $message_indisponible_contre,
            'carte_grise' => $ctCarteGrise,
            'is_transport' => $is_transport,
        ]);
    }

    /**
     * @Route("/recherche_visite_resultat", name="app_ct_app_visite_recherche_visite_resultat", methods={"GET", "POST"})
     */
    public function RechercheVisiteResultat(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $recherche = "";
        $ctVisite = new CtVisite();
        $ctCarteGrise = new CtCarteGrise();
        if($request){
            if($request->request->get("immatriculation")){
                $recherche = strtoupper($request->request->get('immatriculation'));
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            if($request->query->get("immatriculation")){
                $recherche = strtoupper($request->query->get('immatriculation'));
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            if($request->request->get("search-numero-serie")){
                $recherche = strtoupper($request->request->get('search-numero-serie'));
                $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            if($request->query->get("search-numero-serie")){
                $recherche = strtoupper($request->query->get('search-numero-serie'));
                $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            //$ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
            /* if($ctCarteGrise == null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id], ["id" => "DESC"]);
            } */
            //var_dump($ctCarteGrise);
            if($ctCarteGrise != null){
                $ctVisite = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise, "vst_is_active" => 1], ["id" => "DESC"]);
                //$centre = $this->getUser()->getCtCentreId();
                // $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $recherche, "centre" => $centre]);
                //$form_visite = $this->createForm(CtVisiteVisiteDisableType::class, $ctVisite, ["immatriculation" => $recherche, "disable" => true]);
                if($ctVisite != null){
                    $form_visite = $this->createForm(CtVisiteVisiteDisableType::class, $ctVisite, ["immatriculation" => $recherche, "disable" => true]);
                    $form_visite->handleRequest($request);
                    return $this->render('ct_app_visite/recherche_visite_vue.html.twig', [
                        'form_visite' => $form_visite->createView(),
                        'immatriculation' => $recherche,
                        'id' => $ctVisite->getId(),
                    ]);
                }
            }
        }
        return $this->redirectToRoute('app_ct_app_visite_recherche_visite');
    }

    /**
     * @Route("/modification_visite", name="app_ct_app_visite_modification_visite", methods={"GET", "POST"})
     */
    public function ModificationVisite(Request $request, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctVisite = new CtVisite();
        $ctVisite_new = new CtVisite();
        $ctCarteGrise = new CtCarteGrise();
        $message = "";
        $enregistrement_ok = False;
        $immatriculation = "";
        $centre = $this->getUser()->getCtCentreId();
        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
        }
        if($request->request->get('search-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('search-numero-serie')){
            $recherche = strtoupper($request->query->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
        }
        if($request->query->get('search-identification')){
            $recherche = strtoupper($request->query->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }

        if($ctCarteGrise != null){
            $ctVisite->setCtCarteGriseId($ctCarteGrise);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        $ctVisite = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise, "vst_is_active" => 1], ["id" => "DESC"]);
        $ctVisite->setCtCentreId($this->getUser()->getCtCentreId());

        $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $immatriculation, "centre" => $centre]);
        //$form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $immatriculation]);
        $form_visite->handleRequest($request);
        /* if($request->request->get('immatriculation')){
            $immatriculation = strtoupper($request->request->get('immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
        } */

        if ($form_visite->isSubmitted() && $form_visite->isValid()) {
            $ctVisite_new = $ctVisite;
            if($request->request->get('ct_visite_visite')){
                //$recherche = $request->request->get('ct_visite_visite');
                $recherche = $request->request->get('ct_visite_visite');
                $rech = strtoupper($recherche['vst_observation']);
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $rech], ["id" => "DESC"]);

            }
            //$ctVisite_new->setCtCarteGriseId($ctCarteGrise->getId());
            $ctVisite_new->setCtCarteGriseId($ctCarteGrise);
            $ctVisite_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctVisite_new->setCtTypeVisiteId($ctVisite->getCtTypeVisiteId());
            $ctVisite_new->setCtUsageId($ctVisite->getCtUsageId());
            $ctVisite_new->setCtUserId($this->getUser());
            $ctVisite_new->setCtVerificateurId($ctVisite->getCtVerificateurId());
            //$ctVisite_new->setVstNumPv($ctVisite_new->getId().'/'.$ctVisite_new->getCtCentreId()->getCtProvinceId()->getPrvNom().'/'.$ctVisite_new->getCtTypeVisiteId().'/'.date("Y"));
            $date_expiration = new \DateTime();
            $date_expiration->modify('+'.$ctVisite->getCtUsageId()->getUsgValidite().' month');
            //$date_expiration = $date_expiration->format('Y-m-d');
            $ctVisite_new->setVstDateExpiration($ctVisite_new->getVstDateExpiration());
            $date = new \DateTime();
            $ctVisite_new->setVstNumFeuilleCaisse($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisite_new->setVstCreated($ctVisite->getVstCreated());
            $ctVisite_new->setVstUpdated(new \DateTime());
            $ctVisite_new->setCtUtilisationId($ctCarteGrise->getCtUtilisationId());
            $anml = $ctVisite_new->getVstAnomalieId();
            $ctVisite_new->setVstIsApte($anml->count()>0?false:true);
            $ctVisite_new->setVstIsContreVisite(false);
            $ctVisite_new->setVstDureeReparation($ctVisite->getVstDureeReparation());
            $liste_extra = $ctVisite->getCtExtraVentes();
            //var_dump($ctVisite->getCtExtraVentes());
            foreach($liste_extra as $extra){
                $ctVisite_new->addCtExtraVente($extra);
            }
            //$ctVisite_new->setVstAnomalieId($ctVisite->getVstAnomalieId());
            $liste_anomalie =$ctVisite->getVstAnomalieId();
            foreach($liste_anomalie as $anomalie){
                $ctVisite_new->addVstAnomalieId($anomalie);
            }
            $liste_imprime = $ctVisite->getVstImprimeTechId();
            foreach($liste_imprime as $imprime){
                $ctVisite_new->addVstImprimeTechId($imprime);
            }
            $ctVisite_new->setVstIsActive(true);
            $ctVisite_new->setVstGenere(0);
            $ctVisite_new->setVstObservation($ctVisite->getVstObservation().", visite modifier, ID initial : ".$ctVisite->getId().", date modification ".$date->format("d/m/Y"));

            $ctVisiteRepository->add($ctVisite_new, true);
            $ctVisite_new->setVstNumPv($ctVisite_new->getId().'/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisiteRepository->add($ctVisite_new, true);

            /* if($ctVisite->getId() != null && $ctVisite->getId() < $ctVisite_new->getId()){
                $ctVisite->setVstIsActive(false);
                $ctVisite->setVstUpdated(new \DateTime());

                $ctVisiteRepository->add($ctVisite, true);
            } */

            $message = "Visite ajouter avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            return $this->redirectToRoute('app_ct_app_visite_recapitulation_modification', ["id" => $ctVisite_new->getId(), "old" => $ctVisite->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ct_app_visite/edit_visite.html.twig', [
            //'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
            //'form_fiche_verificateur' => $form_fiche_verificateur->createView(),
            //'form_liste_anomalies' => $form_liste_anomalies->createView(),
            'form_visite' => $form_visite->createView(),
            'immatriculation' => $immatriculation,
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
        ]);
    }

    /**
     * @Route("/recapitulation_modification/{id}/{old}", name="app_ct_app_visite_recapitulation_modification", methods={"GET", "POST"})
     */
    public function RecapitulationModification(Request $request, int $id, int $old, CtVisiteRepository $ctVisiteRepository, CtVisite $ctVisite, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $visite_old = $ctVisiteRepository->findOneBy(["id" => $old], ["id" => "DESC"]);
        $visite_old->setVstIsActive(false);
        $visite_old->setVstUpdated(new \DateTime());
        $ctVisiteRepository->add($visite_old, true);
        return $this->render('ct_app_visite/resume_mutation.html.twig', [
            'ct_visite' => $ctVisite,
        ]);
    }

    /**
     * @Route("/recapitulation_visite_contre/{id}/{old}", name="app_ct_app_visite_recapitulation_visite_contre", methods={"GET", "POST"})
     */
    public function RecapitulationVisiteContre(Request $request, int $id, int $old, CtVisiteRepository $ctVisiteRepository, CtVisite $ctVisite, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $visite_old = $ctVisiteRepository->findOneBy(["id" => $old], ["id" => "DESC"]);
        $visite_old->setVstIsActive(false);
        //$visite_old->setVstUpdated(new \DateTime());
        $ctVisiteRepository->add($visite_old, true);
        return $this->render('ct_app_visite/resume_visite.html.twig', [
            'ct_visite' => $ctVisite,
        ]);
    }

    /**
     * @Route("/recherche_duplicata_visite", name="app_ct_app_visite_recherche_duplicata_visite", methods={"GET", "POST"})
     */
    public function RechercheDuplicataVisite(Request $request): Response
    {
        // efa ok ko ito
        return $this->render('ct_app_visite/recherche_duplicata.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/recherche_duplicata_visite_resultat", name="app_ct_app_visite_recherche_duplicata_visite_resultat", methods={"GET", "POST"})
     */
    public function RechercheDuplicataVisiteResultat(Request $request, CtAutreTarifRepository $ctAutreTarifRepository, CtAutreVenteRepository $ctAutreVenteRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtAutreVenteRepository $ctAutreRepository, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechinqueRepository, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $recherche = "";
        $ctVisite = new CtVisite();
        $ctCarteGrise = new CtCarteGrise();
        $form_autre_vente = $this->createFormBuilder()
            ->add('auv_extra', EntityType::class, [
                'label' => 'Extra Duplicata',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi select',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'query_builder' => function(CtVisiteExtraRepository $ctVisiteExtraRepository){
                    $qb = $ctVisiteExtraRepository->createQueryBuilder('u');
                    return $qb
                        ->orderBy('u.id', 'ASC')
                        ->setMaxResults(11)
                    ;
                },
                'required' => false,
                'disabled' => false,
            ])
            ->getForm();
        $form_autre_vente->handleRequest($request);
        if($request){
            $auv = $request->request->get('form');
            if(length($auv["auv_extra"]) > 0){
                $usage_it = $ctUsageImprimeTechinqueRepository->findOneBy(["id" => 2]);
                $autre_tarif = $ctAutreTarifRepository->findOneBy(["ct_usage_imprime_technique_id" => $usage_it], ["aut_arrete" => "DESC"]);
                $autre_vente_extra = $ctVisiteExtraRepository->findOneBy(["vste_libelle" => "PVO"]);
                $date = new \DateTime();
                $identification = $auv["id"];
                $ct_autre_vente = new CtAutreVente();
                $ct_autre_vente->setCtUsageIt($usage_it);
                $ct_autre_vente->setCtAutreTarifId($autre_tarif);
                $ct_autre_vente->setAuvIsVisible(true);
                $ct_autre_vente->setUserId($this->getUser());
                $ct_autre_vente->setVerificateurId(null);
                $ct_autre_vente->setCtCarteGriseId(null);
                $ct_autre_vente->setCtCentreId($this->getUser()->getCtCentreId());
                $ct_autre_vente->setAuvCreatedAt(new \DateTime());
                $ct_autre_vente->setControleId($identification);
                $ct_autre_vente->addAuvExtra($autre_vente_extra);
                foreach($auv["auv_extra"] as $a){
                    $autre_vente_extra = $ctVisiteExtraRepository->findOneBy(["id" => $a]);
                    $ct_autre_vente->addAuvExtra($autre_vente_extra);
                }
                $ct_autre_vente->setAuvValidite("");
                $ct_autre_vente->setAuvItineraire("");
                $ctAutreVenteRepository->add($ct_autre_vente, true);
                $ct_autre_vente->setAuvNumPv($ct_autre_vente->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ct_autre_vente->getCtCentreId()->getCtrCode().'/'.'AUV/'.$date->format("Y"));
                $ctAutreVenteRepository->add($ct_autre_vente, true);
                $id = $auv["id"];

                return $this->redirectToRoute('app_ct_app_imprimable_proces_verbal_visite_duplicata', ['id' => $id]);
            }
            if($request->request->get("immatriculation")){
                $recherche = strtoupper($request->request->get('immatriculation'));
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            if($request->query->get("immatriculation")){
                $recherche = strtoupper($request->query->get('immatriculation'));
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            if($request->request->get("search-numero-serie")){
                $recherche = strtoupper($request->request->get('search-numero-serie'));
                $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            if($request->query->get("search-numero-serie")){
                $recherche = strtoupper($request->query->get('search-numero-serie'));
                $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
            }
            if($ctCarteGrise != null){
                $ctVisite = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise, "vst_is_active" => 1], ["id" => "DESC"]);
                if($ctVisite != null){
                    $form_visite = $this->createForm(CtVisiteVisiteDuplicataType::class, $ctVisite, ["immatriculation" => $recherche, "disable" => true]);
                    $form_visite->handleRequest($request);
                    return $this->render('ct_app_visite/recherche_duplicata_visite_vue.html.twig', [
                        'form_visite' => $form_visite->createView(),
                        'form_autre_vente' => $form_autre_vente->createView(),
                        'immatriculation' => $recherche,
                        'id' => $ctVisite->getId(),
                    ]);
                }
            }
        }
        return $this->redirectToRoute('app_ct_app_visite_recherche_duplicata_visite');
    }

    /**
     * @Route("/recherche_visite_technique_special", name="app_ct_app_visite_recherche_visite_technique_special", methods={"GET", "POST"})
     */
    public function RechercheVisiteSpecial(Request $request): Response
    {
        // efa ok ko ito
        return $this->render('ct_app_visite/recherche_visite_special.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/creer_visite_technique_speciale", name="app_ct_app_visite_creer_visite_technique_speciale", methods={"GET", "POST"})
     */
    public function CreerVisiteTechniqueSpeciale(Request $request, CtVehiculeRepository $ctVehiculeRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechinqueRepository, CtAutreTarifRepository $ctAutreTarifRepository, CtCarteGriseRepository $ctCarteGriseRepository, CtAutreVenteRepository $ctAutreVenteRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        if($request->request->get('immatriculation')){
            $recherche = strtoupper($request->request->get('immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('immatriculation')){
            $recherche = strtoupper($request->query->get('immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->request->get("search-numero-serie")){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get("search-numero-serie")){
            $recherche = strtoupper($request->query->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        $ctAutreVente = new CtAutreVente();
        $ctVisite = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise, "vst_is_active" => 1], ["id" => "DESC"]);
        
        $form_autre_vente = $this->createForm(CtAutreVenteAutreVenteType::class, $ctAutreVente, ["centre" => $this->getUser()->getCtCentreId()]);
        if($ctVisite != null){
            $usage = $ctUsageImprimeTechinqueRepository->findOneBy(["id" => 8]);
            $immatriculation = $ctVisite->getCtCarteGriseId()->getCgImmatriculation();
            $form_autre_vente = $this->createForm(CtAutreVenteVisiteSpecialType::class, $ctAutreVente, ["centre" => $this->getUser()->getCtCentreId(), "controle" => $ctVisite->getId(), "usage" => $usage, "immatriculation" => $immatriculation]);
        }
        $form_autre_vente->handleRequest($request);

        if ($form_autre_vente->isSubmitted() && $form_autre_vente->isValid()) {
            $usage_it = $ctUsageImprimeTechinqueRepository->findOneBy(["id" => 8]);
            $autre_tarif = $ctAutreTarifRepository->findOneBy(["ct_usage_imprime_technique_id" => $usage_it], ["aut_arrete" => "DESC"]);
            $autre_vente_extra = $ctVisiteExtraRepository->findOneBy(["vste_libelle" => "PVO"]);
            $identification = $ctVisite->getId();
            $date = new \DateTime();
            $ct_autre_vente = new CtAutreVente();
            $ct_autre_vente->setCtUsageIt($usage_it);
            $ct_autre_vente->setCtAutreTarifId($autre_tarif);
            $ct_autre_vente->setAuvIsVisible(true);
            $ct_autre_vente->setUserId($this->getUser());
            //$ct_autre_vente->setVerificateurId(null);
            $ct_autre_vente->setCtCarteGriseId($ctCarteGrise);
            $ct_autre_vente->setCtCentreId($this->getUser()->getCtCentreId());
            $ct_autre_vente->setAuvCreatedAt(new \DateTime());
            $ct_autre_vente->setControleId($identification);
            $ct_autre_vente->addAuvExtra($autre_vente_extra);
            /* foreach($auv["auv_extra"] as $a){
                $autre_vente_extra = $ctVisiteExtraRepository->findOneBy(["id" => $a]);
                $ct_autre_vente->addAuvExtra($autre_vente_extra);
            } */
            //$ct_autre_vente->setAuvValidite("");
            //$ct_autre_vente->setAuvItineraire("");
            $ctAutreVenteRepository->add($ct_autre_vente, true);
            $ct_autre_vente->setAuvNumPv($ct_autre_vente->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ct_autre_vente->getCtCentreId()->getCtrCode().'/'.'AUV/'.$date->format("Y"));
            $ctAutreVenteRepository->add($ct_autre_vente, true);
            //$id = $auv["id"];

            //return $this->redirectToRoute('app_ct_autre_vente_index', [], Response::HTTP_SEE_OTHER);
        }
        if($ctVisite == null){
            return $this->redirectToRoute('app_ct_app_visite_recherche_visite_technique_special');
        }

        return $this->renderForm('ct_app_visite/creer_visite_speciale.html.twig', [
            'ct_autre_vente' => $ctAutreVente,
            'form_autre_vente' => $form_autre_vente,
        ]);        
    }

    /**
     * @Route("/recherche_visite_authenticite", name="app_ct_app_visite_recherche_visite_authenticite", methods={"GET", "POST"})
     */
    public function RechercheVisiteAuthenticite(Request $request): Response
    {
        // efa ok ko ito
        return $this->render('ct_app_visite/recherche_authenticite.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/creer_attestation_authenticite", name="app_ct_app_visite_creer_attestation_authenticite", methods={"GET", "POST"})
     */
    public function CreerAttestationAuthenticite(Request $request, CtVehiculeRepository $ctVehiculeRepository, CtUserRepository $ctUserRepository, CtVisiteRepository $ctVisiteRepository, CtVisiteExtraRepository $ctVisiteExtraRepository, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechinqueRepository, CtAutreTarifRepository $ctAutreTarifRepository, CtCarteGriseRepository $ctCarteGriseRepository, CtAutreVenteRepository $ctAutreVenteRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        if($request->request->get('immatriculation')){
            $recherche = strtoupper($request->request->get('immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get('immatriculation')){
            $recherche = strtoupper($request->query->get('immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->request->get("search-numero-serie")){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        if($request->query->get("search-numero-serie")){
            $recherche = strtoupper($request->query->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "cg_is_active" => 1], ["id" => "DESC"]);
        }
        $ctAutreVente = new CtAutreVente();
        $ctVisite = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise, "vst_is_active" => 1], ["id" => "DESC"]);
        
        $form_autre_vente = $this->createForm(CtAutreVenteAutreVenteType::class, $ctAutreVente, ["centre" => $this->getUser()->getCtCentreId()]);
        if($ctVisite != null){
            $usage = $ctUsageImprimeTechinqueRepository->findOneBy(["id" => 3]);
            $immatriculation = $ctVisite->getCtCarteGriseId()->getCgImmatriculation();
            $form_autre_vente = $this->createForm(CtAutreVenteAuthenticiteType::class, $ctAutreVente, ["centre" => $this->getUser()->getCtCentreId(), "controle" => $ctVisite->getId(), "usage" => $usage, "immatriculation" => $immatriculation]);
        }
        $form_autre_vente->handleRequest($request);

        if ($form_autre_vente->isSubmitted() && $form_autre_vente->isValid()) {
            $auv = $request->request->get('ct_autre_vente_authenticite');
            $usage_it = $ctUsageImprimeTechinqueRepository->findOneBy(["id" => 3]);
            $autre_tarif = $ctAutreTarifRepository->findOneBy(["ct_usage_imprime_technique_id" => $usage_it], ["aut_arrete" => "DESC"]);
            $autre_vente_extra = $ctVisiteExtraRepository->findOneBy(["vste_libelle" => "PVO"]);
            $identification = $ctVisite->getId();
            $date = new \DateTime();
            //var_dump($auv['verificateur_id']);
            $ct_autre_vente = new CtAutreVente();
            $ct_autre_vente->setCtUsageIt($usage_it);
            $ct_autre_vente->setCtAutreTarifId($autre_tarif);
            $ct_autre_vente->setAuvIsVisible(true);
            $ct_autre_vente->setUserId($this->getUser());
            $ct_autre_vente->setVerificateurId($ctUserRepository->findOneBy(["id" => $auv['verificateur_id']]));
            $ct_autre_vente->setCtCarteGriseId($ctCarteGrise);
            $ct_autre_vente->setCtCentreId($this->getUser()->getCtCentreId());
            $ct_autre_vente->setAuvCreatedAt(new \DateTime());
            $ct_autre_vente->setControleId($identification);
            $ct_autre_vente->addAuvExtra($autre_vente_extra);
            if($auv['lateral_arriere'] == true){
                $ct_autre_vente->setAuvItineraire($ct_autre_vente->getAuvItineraire().", latérales arrière");
            }
            if($auv['lateral_avant'] == true){
                $ct_autre_vente->setAuvItineraire($ct_autre_vente->getAuvItineraire().", latérales avant");
            }
            if($auv['lunette_arriere'] == true){
                $ct_autre_vente->setAuvItineraire($ct_autre_vente->getAuvItineraire().", lunette arrière");
            }
            if($auv['pare_brise'] == true){
                $ct_autre_vente->setAuvItineraire($ct_autre_vente->getAuvItineraire().", pare-brise");
            }
            /* foreach($auv["option_fumee"] as $a){
                //$autre_vente_extra = $ctVisiteExtraRepository->findOneBy(["id" => $a]);
                $ct_autre_vente->setAuvItineraire($ct_autre_vente->getAuvItineraire().", ".$a);
            } */
            //$ct_autre_vente->setAuvValidite("");
            //$ct_autre_vente->setAuvItineraire("");
            $ctAutreVenteRepository->add($ct_autre_vente, true);
            $ct_autre_vente->setAuvNumPv($ct_autre_vente->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ct_autre_vente->getCtCentreId()->getCtrCode().'/'.'AUV/'.$date->format("Y"));
            $ctAutreVenteRepository->add($ct_autre_vente, true);
            //$id = $auv["id"];


            //return $this->redirectToRoute('app_ct_autre_vente_index', [], Response::HTTP_SEE_OTHER);
        }
        if($ctVisite == null){
            return $this->redirectToRoute('app_ct_app_visite_recherche_visite_authenticite');
        }

        return $this->renderForm('ct_app_visite/creer_authenticite.html.twig', [
            'ct_autre_vente' => $ctAutreVente,
            'form_autre_vente' => $form_autre_vente,
        ]);
    }

    /**
     * @Route("/it_visite/{id}", name="app_ct_app_visite_it_visite", methods={"GET", "POST"})
     */
    public function ItVisite(Request $request, int $id, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechniqueRepository, CtVisiteRepository $ctVisiteRepository, CtImprimeTechRepository $ctImprimeTechRepository, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $ct_imprime_tech_use = new CtImprimeTechUse();
        $usage = $ctUsageImprimeTechniqueRepository->findOneby(["id" => 10]);
        //$ct_imprime_tech_use = $ctImprimeTechUseRepository->findOneBy(["id" => $id]);
        $form_imprime_tech_use = $this->createForm(CtImprimeTechUseModulableType::class, $ct_imprime_tech_use, ["multiple" => true, "carte" => true,"controle" => $id,"usage" => $usage, "centre" => $this->getUser()->getCtCentreId()]);
        $form_imprime_tech_use->handleRequest($request);

        if($form_imprime_tech_use->isSubmitted() && $form_imprime_tech_use->isValid()) {
            //$ct_imprime_tech_use_get->setCtCarrosserieId($form_imprime_tech_use['ct_imprime_tech_use_multiple']['imprime_technique_use_numero']->getData());
            $ct_imprime_tech_use_get = $form_imprime_tech_use['ct_imprime_tech_use_multiple']['imprime_technique_use_numero']->getData();
            foreach($ct_imprime_tech_use_get as $ct_itu){
                $itu_utilisable = false;
                $ct_itu->setItuUsed(1);
                $ct_itu->setCreatedAt(new \DateTime());
                $ct_itu->setCtUserId($this->getUser());
                $ct_itu->setCtControleId($ct_imprime_tech_use->getCtControleId());
                if($ct_imprime_tech_use->getCtUsageItId()->getUitLibelle() == "VISITE"){
                    $ct_itu->setCtUsageItId($ct_imprime_tech_use->getCtUsageItId());
                    $controle = $ctVisiteRepository->findOneBy(["id" => $ct_imprime_tech_use->getCtControleId(), "ct_centre_id" => $this->getUser()->getCtCentreId()]);
                    if($controle != null){
                        $itu_utilisable = true;
                    }
                }
                // asiana même principe ny utilisation sasany rehetra
                if($itu_utilisable == true){
                    $ctImprimeTechUseRepository->add($ct_itu, true);
                    //return $this->redirectToRoute('app_ct_app_imprime_technique_mise_a_jour_utilisation', [], Response::HTTP_SEE_OTHER);
                }
            }
            return $this->redirectToRoute('app_ct_app_visite_recapitulation_visite', ["id" => $id], Response::HTTP_SEE_OTHER);
        }
        /* return $this->render('ct_app_imprime_technique/mise_a_jour_multiple.html.twig', [
            'form_imprime_tech_use' => $form_imprime_tech_use->createView(),
        ]); */
        return $this->redirectToRoute('app_ct_app_visite_it_visite', ["id" => $id], Response::HTTP_SEE_OTHER);
    }
}
