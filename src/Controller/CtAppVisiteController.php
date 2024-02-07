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
use App\Form\CtCarteGriseType as FormCtCarteGriseType;
use App\Repository\CtTypeVisiteRepository;
use App\Repository\CtCarteGriseRepository;
use App\Repository\CtVehiculeRepository;
use App\Repository\CtUserRepository;
use App\Form\CtRensCarteGriseType;
use App\Form\CtVisiteCarteGriseType;
use App\Form\CtRensVehiculeType;
use App\Form\CtVehiculeType;
use App\Form\CtVisiteVehiculeType;
use App\Form\CtVisiteVisiteType;
use App\Form\CtVisiteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\DataTransformer\IssueToNumberTransformer;
use App\Repository\CtUtilisationRepository;
use App\Repository\CtVisiteRepository;
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
        if($request->request->get('search-immatriculation')){
            $recherche = $request->request->get('search-immatriculation');
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"], ["cg_is_active" => true]);
        }
        if($request->request->get('search-numero-serie')){
            $recherche = $request->request->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id], ["id" => "DESC"], ["cg_is_active" => true]);
        }
        if($request->request->get('ssearch-identification')){
            $recherche = $request->request->get('search-identification');
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche], ["id" => "DESC"], ["cg_is_active" => true]);
        }
        $ctCarteGrise_new = new CtCarteGrise();
        $ctVehicule = new CtVehicule();
        $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise);
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
     * @Route("/creer_visite", name="app_ct_app_visite_creer_visite", methods={"GET", "POST"})
     */
    public function CreerVisite(Request $request, CtTypeVisiteRepository $ctTypeVisiteRepository, CtUtilisationRepository $ctUtilisationRepository, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
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
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id], ["id" => "DESC"]);
            }
        }
        if($request->request->get('search-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche], ["id" => "DESC"]);
        }

        if($ctCarteGrise != null){
            $ctVisite->setCtCarteGriseId($ctCarteGrise);
            $immatriculation = $ctCarteGrise->getCgImmatriculation();
        }
        $ctVisite->setCtCentreId($this->getUser()->getCtCentreId());

        $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $immatriculation, "centre" => $centre]);
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
            $ctVisite_new->setVstDateExpiration($date_expiration);
            $date = new \DateTime();
            $ctVisite_new->setVstNumFeuilleCaisse($date->format('d').'/'.$date->format('m').'/'.$ctVisite->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisite_new->setVstCreated(new \DateTime());
            $ctVisite_new->setVstUpdated($ctVisite->getVstUpdated());
            $ctVisite_new->setCtUtilisationId($ctVisite->getCtUtilisationId());
            $anml = $ctVisite_new->getVstAnomalieId();
            $ctVisite_new->setVstIsApte($anml->count()>0?false:true);
            $ctVisite_new->setVstIsContreVisite(false);
            $ctVisite_new->setVstDureeReparation($ctVisite->getVstDureeReparation());
            $liste_extra = $ctVisite->getCtExtraVentes();
            var_dump($ctVisite->getCtExtraVentes());
            foreach($liste_extra as $extra){
                $ctVisite_new->addCtExtraVente($extra);
            }
            $ctVisite_new->setVstIsActive(true);
            $ctVisite_new->setVstGenere(0);
            $ctVisite_new->setVstObservation(" - ");

            $ctVisiteRepository->add($ctVisite_new, true);
            $ctVisite_new->setVstNumPv($ctVisite_new->getId().'/'.$ctVisite->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisiteRepository->add($ctVisite_new, true);

            if($ctVisite->getId() != null && $ctVisite->getId() < $ctVisite_new->getId()){
                $ctVisite->setVstIsActive(false);
                $ctVisite->setVstUpdated(new \DateTime());

                $ctVisiteRepository->add($ctVisite, true);
            }

            $message = "Visite ajouter avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            return $this->redirectToRoute('app_ct_app_visite_recapitulation_visite', ["id" => $ctVisite_new->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ct_app_visite/creer_visite.html.twig', [
            'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
            'form_fiche_verificateur' => $form_fiche_verificateur->createView(),
            'form_liste_anomalies' => $form_liste_anomalies->createView(),
            'form_visite' => $form_visite->createView(),
            'immatriculation' => $immatriculation,
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
        ]);
    }

    /**
     * @Route("/recapitulation_visite/{id}", name="app_ct_app_visite_recapitulation_visite", methods={"GET", "POST"})
     */
    public function RecapitulationVisite(Request $request, int $id, CtVisite $ctVisite): Response
    {
        // efa ok ito
        return $this->render('ct_app_visite/resume_visite.html.twig', [
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
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
            $recherche_ok = true;
        }
        if($request->request->get('search-numero-serie')){
            $recherche = strtoupper($request->request->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id], ["id" => "DESC"]);
            }
            $recherche_ok = true;
        }
        if($request->request->get('ssearch-identification')){
            $recherche = strtoupper($request->request->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche], ["id" => "DESC"]);
            $recherche_ok = true;
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
            $recherche_ok = true;
        }
        if($request->query->get('search-numero-serie')){
            $recherche = strtoupper($request->query->get('search-numero-serie'));
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id], ["id" => "DESC"]);
            }
            $recherche_ok = true;
        }
        if($request->query->get('ssearch-identification')){
            $recherche = strtoupper($request->query->get('search-identification'));
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_num_identification" => $recherche], ["id" => "DESC"]);
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
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $rech], ["id" => "DESC"]);

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
            $ctVisite_contre->setVstNumFeuilleCaisse($date->format('d').'/'.$date->format('m').'/'.$ctVisite->getCtCentreId()->getCtrCode().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));
            $ctVisite_contre->setVstCreated(new \DateTime());
            $ctVisite_contre->setVstUpdated($ctVisite->getVstUpdated());
            $ctVisite_contre->setCtUtilisationId($ctVisite->getCtUtilisationId());
            $anml = $ctVisite_contre->getVstAnomalieId();
            $ctVisite_contre->setVstIsApte($anml->count()>0?false:true);
            $ctVisite_contre->setVstIsContreVisite(true);
            $ctVisite_contre->setVstDureeReparation($ctVisite->getVstDureeReparation());
            $ctVisite_contre->setVstIsActive(true);
            $ctVisite_contre->setVstGenere(0);
            $ctVisite_contre->setVstObservation($ctVisite->getVstObservation()." CONTRE DU ID : ".$ctVisite->getId());

            $ctVisiteRepository->add($ctVisite_contre, true);
            $ctVisite_contre->setVstNumPv($ctVisite_contre->getId().'/'.$ctVisite->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId().'/'.$ctVisite->getCtTypeVisiteId().'/'.$date->format("Y"));        
            $ctVisiteRepository->add($ctVisite_contre, true);

            if($ctVisite->getId() != null && $ctVisite->getId() < $ctVisite_contre->getId()){
                $ctVisite->setVstIsActive(false);
                $ctVisite->setVstUpdated(new \DateTime());

                $ctVisiteRepository->add($ctVisite, true);
            }
            $message = "Contre ajouter avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            return $this->redirectToRoute('app_ct_app_visite_recapitulation_visite', ["id" => $ctVisite_contre->getId()], Response::HTTP_SEE_OTHER);
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
     * @Route("/recherche_visite_immatriculation", name="app_ct_app_visite_recherche_visite_immatriculation", methods={"GET", "POST"})
     */
    public function RechercheVisiteImmatriculation(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $recherche = "";
        if($request){
            if($request->request->get("immatriculation")){
                $recherche = strtoupper($request->request->get('immatriculation'));
            }
            if($request->query->get("immatriculation")){
                $recherche = strtoupper($request->query->get('immatriculation'));
            }
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
            $ctVisite = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise], ["id" => "DESC"]);
            $centre = $this->getUser()->getCtCentreId();
            $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $recherche, "centre" => $centre]);
            $form_visite->handleRequest($request);
            return $this->render('ct_app_visite/recherche_visite_vue.html.twig', [
                'form_visite' => $form_visite->createView(),
                'immatriculation' => $recherche,
                'id' => $ctVisite->getId(),
            ]);
        }
        return $this->redirectToRoute('app_ct_app_visite_recherche_visite');
    }

    /**
     * @Route("/recherche_visite_numero_serie", name="app_ct_app_visite_recherche_visite_numero_serie", methods={"GET", "POST"})
     */
    public function RechercheVisiteNumeroSerie(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $recherche = "";
        $vehicule_id = New CtVehicule();
        if($request){
            if($request->request->get("immatriculation")){
                $recherche = strtoupper($request->request->get('immatriculation'));
                $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            }
            if($request->query->get("immatriculation")){
                $recherche = strtoupper($request->query->get('immatriculation'));
                $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            }
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"]);
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id], ["id" => "DESC"]);
            $ctVisite = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise], ["id" => "DESC"]);
            $centre = $this->getUser()->getCtCentreId();
            $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite, ["immatriculation" => $recherche, "centre" => $centre]);
            $form_visite->handleRequest($request);
            return $this->render('ct_app_visite/recherche_visite_vue.html.twig', [
                'form_visite' => $form_visite->createView(),
                'immatriculation' => $recherche,
                'id' => $ctVisite->getId(),
            ]);
        }
        return $this->redirectToRoute('app_ct_app_visite_recherche_visite');
    }

    /**
     * @Route("/recherche_contre_numero_serie", name="app_ct_app_visite_recherche_contre_numero_serie", methods={"GET", "POST"})
     */
    public function RechercheContreImmatriculation(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        $contre = false;
        if($request->request->get('search-immatriculation')){
            $recherche = $request->request->get('search-immatriculation');
            $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["cg_immatriculation" => $recherche], ["id" => "DESC"], ["cg_is_active" => true]);
            $ctVisite_contre = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise], ["id" => "DESC"]);
        }

        if($ctCarteGrise != null){
            $ctVisite_old = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise], ["id" => "DESC"], ["cg_is_active" => true]);
            if($ctVisite_old != null && $ctVisite_old->isVstIsActive() == true && $ctVisite_old->isVstIsContreVisite() == false){
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
            $ctVisite->setCtCarteGriseId($ctCarteGrise);
        }
        $form_visite = $this->createFormBuilder($ctVisite)
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Centre',
                'class' => CtCentre::class,
            ])
            ->add('ct_type_visite_id', EntityType::class, [
                'label' => 'Type de visite',
                'class' => CtTypeVisite::class,
            ])
            ->add('ct_usage_id', EntityType::class, [
                'label' => 'Usage',
                'class' => CtUsage::class,
            ])
            ->add('ct_utilisation_id', EntityType::class, [
                'label' => 'Utilisation',
                'class' => CtUtilisation::class,
            ])
            ->add('vst_anomalie_id', EntityType::class, [
                'label' => 'Anomalies',
                'class' => CtAnomalie::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi is_anomalie',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            /* ->add('vst_date_expiration', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ]) */
            ->add('ct_verificateur_id', EntityType::class, [
                'label' => 'Vérificateur',
                'class' => CtUser::class,
                'query_builder' => function(CtUserRepository $ctUserRepository){
                    $qb = $ctUserRepository->createQueryBuilder('u');
                    return $qb
                        ->Where('u.ct_role_id = :val1')
                        ->andWhere('u.ct_centre_id = :val2')
                        ->setParameter('val1', 14)
                        ->setParameter('val2', $this->getUser()->getCtCentreId())
                    ;
                }
            ])
            ->add('vst_extra', EntityType::class, [
                'label' => 'Extra',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('ct_carte_grise_id', CtVisiteCarteGriseType::class, [
                'label' => 'Carte Grise',
                'disabled' => true,
            ])
            ->add('vst_duree_reparation', TextType::class, [
                'label' => 'Durée de reparation accordée',
                'disabled' => true,
            ])
            ->getForm();
        $form_visite->handleRequest($request);

        return $this->render('ct_app_visite/contre_visite.html.twig', [
            'form_visite' => $form_visite->createView(),
            'contre_ok' => $contre,
        ]);
    }

    /**
     * @Route("/recherche_contre_numero_serie", name="app_ct_app_visite_recherche_contre_numero_serie", methods={"GET", "POST"})
     */
    public function RechercheContreNumeroSerie(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        $contre = false;
        if($request->request->get('search-numero-serie')){
            $recherche = $request->request->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctCarteGrise = $ctCarteGriseRepository->findOneBy(["ct_vehicule_id" => $vehicule_id], ["id" => "DESC"], ["cg_is_active" => true]);
                $ctVisite_contre = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise], ["id" => "DESC"]);
            }
        }

        if($ctCarteGrise != null){
            $ctVisite_old = $ctVisiteRepository->findOneBy(["ct_carte_grise_id" => $ctCarteGrise], ["id" => "DESC"], ["cg_is_active" => true]);
            if($ctVisite_old != null && $ctVisite_old->isVstIsActive() == true && $ctVisite_old->isVstIsContreVisite() == false){
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
            $ctVisite->setCtCarteGriseId($ctCarteGrise);
        }
        $form_visite = $this->createFormBuilder($ctVisite)
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Centre',
                'class' => CtCentre::class,
            ])
            ->add('ct_type_visite_id', EntityType::class, [
                'label' => 'Type de visite',
                'class' => CtTypeVisite::class,
            ])
            ->add('ct_usage_id', EntityType::class, [
                'label' => 'Usage',
                'class' => CtUsage::class,
            ])
            ->add('ct_utilisation_id', EntityType::class, [
                'label' => 'Utilisation',
                'class' => CtUtilisation::class,
            ])
            ->add('vst_anomalie_id', EntityType::class, [
                'label' => 'Anomalies',
                'class' => CtAnomalie::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi is_anomalie',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            /* ->add('vst_date_expiration', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ]) */
            ->add('ct_verificateur_id', EntityType::class, [
                'label' => 'Vérificateur',
                'class' => CtUser::class,
                'query_builder' => function(CtUserRepository $ctUserRepository){
                    $qb = $ctUserRepository->createQueryBuilder('u');
                    return $qb
                        ->Where('u.ct_role_id = :val1')
                        ->andWhere('u.ct_centre_id = :val2')
                        ->setParameter('val1', 14)
                        ->setParameter('val2', $this->getUser()->getCtCentreId())
                    ;
                }
            ])
            ->add('vst_extra', EntityType::class, [
                'label' => 'Extra',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('ct_carte_grise_id', CtVisiteCarteGriseType::class, [
                'label' => 'Carte Grise',
                'disabled' => true,
            ])
            ->add('vst_duree_reparation', TextType::class, [
                'label' => 'Durée de reparation accordée',
                'disabled' => true,
            ])
            ->getForm();
        $form_visite->handleRequest($request);

        return $this->render('ct_app_visite/contre_visite.html.twig', [
            'form_visite' => $form_visite->createView(),
            'contre_ok' => $contre,
        ]);
    }
}
