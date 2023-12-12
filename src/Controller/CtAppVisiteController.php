<?php

namespace App\Controller;

use App\Entity\CtTypeVisite;
use App\Entity\CtVisite;
use App\Entity\CtCarteGrise;
use App\Entity\CtCentre;
use App\Entity\CtVehicule;
use App\Form\CtTypeVisiteType;
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
        // fonction mbola ampidirina eto : maka résultat recherche dia mampiditra ao anaty form
        // raha misy résultat ny recherche dia mampiditra azy ao anatin'ny form
        // mameno ny information sasany amin'ilay entity ireny alohan'ny persistance
        $ctCarteGrise = new CtCarteGrise();
        $form_carte_grise = $this->createForm(CtRensCarteGriseType::class, $ctCarteGrise);
        $form_carte_grise->handleRequest($request);
        $message = "";
        $enregistrement_ok = False;
        
        /* if ($form_vehicule->isSubmitted() && $form_vehicule->isValid() && $form_carte_grise->isSubmitted() && $form_carte_grise->isValid()) {
            try{ 
                $ctVehiculeRepository->add($ctVehicule, true);
                $ctCarteGrise->setCtVehiculeId($ctVehicule);
                $ctCarteGriseRepository->add($ctCarteGrise, true);

                $message = "Enregistrement effectué avec succès du véhicule portant l'immatriculation : ".$ctCarteGrise->getCgImmatriculation();
                $enregistrement_ok = True;
            } catch (Exception $e) {
                $message = "Echec de l'enregistrement du véhicule";
                $enregistrement_ok = False;
            }
        } */
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
    public function CreerVisite(Request $request): Response
    {
        $ctVisite = new CtVisite();
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
                    'style' => 'width:100%;',
                ],
            ])
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Séléctionner le centre',
                'class' => CtCentre::class,
                'attr' => [
                    'style' => 'width:100%;',
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
                    'style' => 'width:100%;',
                ],
            ])
            ->add('ct_type_visite_id', EntityType::class, [
                'label' => 'Séléctionner verificateur',
                'class' => CtTypeVisite::class,
                'attr' => [
                    'style' => 'width:100%;',
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
                    'style' => 'width:100%;',
                ],
            ])
            ->getForm();
        $form_feuille_de_caisse->handleRequest($request);
        $form_fiche_verificateur->handleRequest($request);
        $form_liste_anomalies->handleRequest($request);
        return $this->render('ct_app_visite/creer_visite.html.twig', [
            'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
            'form_fiche_verificateur' => $form_fiche_verificateur->createView(),
            'form_liste_anomalies' => $form_liste_anomalies->createView(),
            'form_visite' => $form_visite->createView(),
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
