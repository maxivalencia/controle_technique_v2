<?php

namespace App\Controller;

use App\Entity\CtTypeVisite;
use App\Entity\CtVisite;
use App\Entity\CtCarteGrise;
use App\Entity\CtCentre;
use App\Entity\CtVehicule;
use App\Form\CtTypeVisiteType;
use App\Entity\CtUser;
use App\Form\CtCarteGriseType;
use App\Form\CtCarteGriseType as FormCtCarteGriseType;
use App\Repository\CtTypeVisiteRepository;
use App\Repository\CtCarteGriseRepository;
use App\Repository\CtVehiculeRepository;
use App\Form\CtRensCarteGriseType;
use App\Form\CtRensVehiculeType;
use App\Form\CtVehiculeType;
use App\Form\CtVisiteVisiteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\DataTransformer\IssueToNumberTransformer;
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

                if($ctCarteGrise->getId() != null && $ctCarteGrise->getId() < $ctCarteGrise->getId()){
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
    public function CreerVisite(Request $request, CtVisiteRepository $ctVisiteRepository, CtVehiculeRepository $ctVehiculeRepository, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctVisite = new CtVisite();
        $ctCarteGrise = new CtCarteGrise();
        $message = "";
        $enregistrement_ok = False;
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
        $ctVisite->setCtCarteGriseId($ctCarteGrise);
        $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite);
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
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Séléctionner le centre',
                'class' => CtCentre::class,
                'attr' => [
                    'class' => 'multi',
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
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('ct_type_visite_id', EntityType::class, [
                'label' => 'Séléctionner verificateur',
                'class' => CtTypeVisite::class,
                'attr' => [
                    'class' => 'multi',
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
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->getForm();
        $form_feuille_de_caisse->handleRequest($request);
        $form_fiche_verificateur->handleRequest($request);
        $form_liste_anomalies->handleRequest($request);

        if ($form_visite->isSubmitted() && $form_visite->isValid()) {
            //$ctVisite->setCtCarteGriseId($ctVisite->);
            $ctVisite->setCtCentreId($this->getUser()->getCtCentreId());
            //$ctVisite->setCtTypeVisiteId$ctVisite->();
            //$ctVisite->setCtUsageId($ctVisite->);
            
            $ctVisite->setCtUserId($this->getUser());
            //$ctVisite->setCtVerificateurId($ctVisite->);
            $ctVisite->setVstNumPv($ctVisite->getId().'/'.$ctVisite->getCtCentreId()->getCtProvinceId()->getPrvNom().'/'.$ctVisite->getCtTypeVisiteId().'/'.date("Y"));
            $date = new \DateTime();
            $date->modify('+'.$ctVisite->getCtUsageId()->getUsgValidite().' month');
            $date = $date->format('Y-m-d');
            $ctVisite->setVstDateExpiration($date);
            $ctVisite->setVstNumFeuilleCaisse($date->format('d').'/'.$ctVisite->getCtCentreId()->getCtrNom().'/'.$ctVisite->getCtTypeVisiteId().'/'.date("Y"));
            $ctVisite->setVstCreated(new \DateTime());
            //$ctVisite->setVstUpdated($ctVisite->);
            //$ctVisite->setCtUtilisationId($ctVisite->);
            $anml = $ctVisite->getVstAnomalieId();
            $ctVisite->setVstIsApte($anml->count()>0?true:false);
            $ctVisite->setVstIsContreVisite(false);
            //$ctVisite->setVstDureeReparation($ctVisite->);
            $ctVisite->setVstIsActive(true);
            $ctVisite->setVstGenere($ctVisite->getVstGenere() +1);
            $ctVisite->setVstObservation($ctVisite->getVstObservation());

            $ctVisiteRepository->add($ctVisite, true);
            $message = "Visite ajouter avec succes";
            $enregistrement_ok = true;
        }

        return $this->render('ct_app_visite/creer_visite.html.twig', [
            'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
            'form_fiche_verificateur' => $form_fiche_verificateur->createView(),
            'form_liste_anomalies' => $form_liste_anomalies->createView(),
            'form_visite' => $form_visite->createView(), 
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
        ]);
    }

    /**
     * @Route("/feuille_de_caisse", name="app_ct_app_visite_feuille_de_caise", methods={"GET", "POST"})
     */
    public function FeuilleDeCaisse(Request $request): Response
    {
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
        return $this->render('ct_app_visite/recherche_visite.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }

    /**
     * @Route("/contre_visite", name="app_ct_app_visite_contre_visite", methods={"GET", "POST"})
     */
    public function ContreVisite(Request $request): Response
    {
        $ctVisite = new CtVisite();
        $form_visite = $this->createForm(CtVisiteVisiteType::class, $ctVisite);
        $form_visite->handleRequest($request);
        return $this->render('ct_app_visite/contre_visite.html.twig', [
            'form_visite' => $form_visite->createView(),
        ]);
    }
}
