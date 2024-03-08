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
use App\Repository\CtDroitPTACRepository;
use App\Repository\CtCentreRepository;
use App\Repository\CtUtilisationRepository;
use App\Repository\CtVehiculeRepository;
use App\Repository\CtTypeVisiteRepository;
use App\Repository\CtUsageTarifRepository;
use App\Repository\CtUsageRepository;
use App\Repository\CtUserRepository;
use App\Entity\CtImprimeTech;
use App\Form\CtImprimeTechType;
use App\Repository\CtImprimeTechRepository;
use App\Entity\CtImprimeTechUse;
use App\Form\CtImprimeTechUseType;
use App\Form\CtImprimeTechUseDisableType;
use App\Form\CtImprimeTechUseMultipleType;
use App\Repository\CtImprimeTechUseRepository;
use App\Entity\CtBordereau;
use App\Form\CtBordereauType;
use App\Form\CtBordereauAjoutType;
use App\Repository\CtBordereauRepository;
use App\Controller\Datetime;
use App\Entity\CtCentre;
use App\Entity\CtTypeReception;
use App\Entity\CtConstAvDedCarac;
use App\Entity\CtGenreCategorie;
use App\Entity\CtVisite;
use App\Entity\CtMarque;
use App\Entity\CtUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @Route("/ct_app_statistique")
 */
class CtAppSatistiqueController extends AbstractController
{
    //#[Route('/ct/app/satistique', name: 'app_ct_app_satistique')]
    /**
     * @Route("/", name="app_ct_app_statistique")
     */
    public function index(): Response
    {
        return $this->render('ct_app_satistique/index.html.twig', [
            'controller_name' => 'CtAppSatistiqueController',
        ]);
    }

    /**
     * @Route("/statistique_visite", name="app_ct_app_statistique_statistique_visite")
     */
    public function StatistiqueVisite(Request $request): Response
    {
        $mois = [
            "Janvier" => 1,
            "Février" => 2,
            "Mars" => 3,
            "Avril" => 4,
            "Mai" => 5,
            "Juin" => 6,
            "Juillet" => 7,
            "Août" => 8,
            "Septembre" => 9,
            "Octobre" => 10,
            "Novembre" => 11,
            "Décembre" => 12,
        ];
        $trimestre = [
            "trimestre 1" => 1,
            "trimestre 2" => 2,
            "trimestre 3" => 3,
            "trimestre 4" => 4,
        ];
        $semestre = [
            "semestre 1" => 1,
            "semestre 2" => 2,
        ];
        $date = new \DateTime();
        $annee_max = intval($date->format('Y'));
        $liste_annee = [
            strval($annee_max) => $annee_max,
            strval($annee_max - 1) => $annee_max - 1,
            strval($annee_max - 2) => $annee_max - 2,
            strval($annee_max - 3) => $annee_max - 3,
            strval($annee_max - 4) => $annee_max - 4,
            strval($annee_max - 5) => $annee_max - 5,
            strval($annee_max - 6) => $annee_max - 6,
            strval($annee_max - 7) => $annee_max - 7,
            strval($annee_max - 8) => $annee_max - 8,
            strval($annee_max - 9) => $annee_max - 9,
            strval($annee_max - 10) => $annee_max - 10,
        ];
        $form_annuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
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
        $form_annuel->handleRequest($request);
        $form_semestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('semestre', ChoiceType::class, [
                'label' => 'Séléctionner le semestre',
                'choices' => $semestre,
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
        $form_semestriel->handleRequest($request);
        $form_trimestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('trimestre', ChoiceType::class, [
                'label' => 'Séléctionner le trimestre',
                'choices' => $trimestre,
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
        $form_trimestriel->handleRequest($request);
        $form_mensuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('mois', ChoiceType::class, [
                'label' => 'Séléctionner le mois',
                'choices' => $mois,
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
        $form_mensuel->handleRequest($request);

        return $this->render('ct_app_satistique/statistique_visite.html.twig', [
            'form_annuel' => $form_annuel->createView(),
            'form_semestriel' => $form_semestriel->createView(),
            'form_trimestriel' => $form_trimestriel->createView(),
            'form_mensuel' => $form_mensuel->createView(),
        ]);
    }

    /**
     * @Route("/statistique_reception", name="app_ct_app_statistique_statistique_reception")
     */
    public function StatistiqueReception(Request $request): Response
    {
        $mois = [
            "Janvier" => 1,
            "Février" => 2,
            "Mars" => 3,
            "Avril" => 4,
            "Mai" => 5,
            "Juin" => 6,
            "Juillet" => 7,
            "Août" => 8,
            "Septembre" => 9,
            "Octobre" => 10,
            "Novembre" => 11,
            "Décembre" => 12,
        ];
        $trimestre = [
            "trimestre 1" => 1,
            "trimestre 2" => 2,
            "trimestre 3" => 3,
            "trimestre 4" => 4,
        ];
        $semestre = [
            "semestre 1" => 1,
            "semestre 2" => 2,
        ];
        $date = new \DateTime();
        $annee_max = intval($date->format('Y'));
        $liste_annee = [
            strval($annee_max) => $annee_max,
            strval($annee_max - 1) => $annee_max - 1,
            strval($annee_max - 2) => $annee_max - 2,
            strval($annee_max - 3) => $annee_max - 3,
            strval($annee_max - 4) => $annee_max - 4,
            strval($annee_max - 5) => $annee_max - 5,
            strval($annee_max - 6) => $annee_max - 6,
            strval($annee_max - 7) => $annee_max - 7,
            strval($annee_max - 8) => $annee_max - 8,
            strval($annee_max - 9) => $annee_max - 9,
            strval($annee_max - 10) => $annee_max - 10,
        ];
        $form_annuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
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
        $form_annuel->handleRequest($request);
        $form_semestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('semestre', ChoiceType::class, [
                'label' => 'Séléctionner le semestre',
                'choices' => $semestre,
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
        $form_semestriel->handleRequest($request);
        $form_trimestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('trimestre', ChoiceType::class, [
                'label' => 'Séléctionner le trimestre',
                'choices' => $trimestre,
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
        $form_trimestriel->handleRequest($request);
        $form_mensuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('mois', ChoiceType::class, [
                'label' => 'Séléctionner le mois',
                'choices' => $mois,
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
        $form_mensuel->handleRequest($request);

        return $this->render('ct_app_satistique/statistique_reception.html.twig', [
            'form_annuel' => $form_annuel->createView(),
            'form_semestriel' => $form_semestriel->createView(),
            'form_trimestriel' => $form_trimestriel->createView(),
            'form_mensuel' => $form_mensuel->createView(),
        ]);
    }

    /**
     * @Route("/statistique_constatation", name="app_ct_app_statistique_statistique_constatation")
     */
    public function StatistiqueConstatation(Request $request): Response
    {
        $mois = [
            "Janvier" => 1,
            "Février" => 2,
            "Mars" => 3,
            "Avril" => 4,
            "Mai" => 5,
            "Juin" => 6,
            "Juillet" => 7,
            "Août" => 8,
            "Septembre" => 9,
            "Octobre" => 10,
            "Novembre" => 11,
            "Décembre" => 12,
        ];
        $trimestre = [
            "trimestre 1" => 1,
            "trimestre 2" => 2,
            "trimestre 3" => 3,
            "trimestre 4" => 4,
        ];
        $semestre = [
            "semestre 1" => 1,
            "semestre 2" => 2,
        ];
        $date = new \DateTime();
        $annee_max = intval($date->format('Y'));
        $liste_annee = [
            strval($annee_max) => $annee_max,
            strval($annee_max - 1) => $annee_max - 1,
            strval($annee_max - 2) => $annee_max - 2,
            strval($annee_max - 3) => $annee_max - 3,
            strval($annee_max - 4) => $annee_max - 4,
            strval($annee_max - 5) => $annee_max - 5,
            strval($annee_max - 6) => $annee_max - 6,
            strval($annee_max - 7) => $annee_max - 7,
            strval($annee_max - 8) => $annee_max - 8,
            strval($annee_max - 9) => $annee_max - 9,
            strval($annee_max - 10) => $annee_max - 10,
        ];
        $form_annuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
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
        $form_annuel->handleRequest($request);
        $form_semestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('semestre', ChoiceType::class, [
                'label' => 'Séléctionner le semestre',
                'choices' => $semestre,
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
        $form_semestriel->handleRequest($request);
        $form_trimestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('trimestre', ChoiceType::class, [
                'label' => 'Séléctionner le trimestre',
                'choices' => $trimestre,
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
        $form_trimestriel->handleRequest($request);
        $form_mensuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('mois', ChoiceType::class, [
                'label' => 'Séléctionner le mois',
                'choices' => $mois,
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
        $form_mensuel->handleRequest($request);

        return $this->render('ct_app_satistique/statistique_constatation.html.twig', [
            'form_annuel' => $form_annuel->createView(),
            'form_semestriel' => $form_semestriel->createView(),
            'form_trimestriel' => $form_trimestriel->createView(),
            'form_mensuel' => $form_mensuel->createView(),
        ]);
    }

    /**
     * @Route("/statistique_imprime_technique", name="app_ct_app_statistique_statistique_imprime_technique")
     */
    public function StatistiqueImprimeTechnique(Request $request): Response
    {
        $mois = [
            "Janvier" => 1,
            "Février" => 2,
            "Mars" => 3,
            "Avril" => 4,
            "Mai" => 5,
            "Juin" => 6,
            "Juillet" => 7,
            "Août" => 8,
            "Septembre" => 9,
            "Octobre" => 10,
            "Novembre" => 11,
            "Décembre" => 12,
        ];
        $trimestre = [
            "trimestre 1" => 1,
            "trimestre 2" => 2,
            "trimestre 3" => 3,
            "trimestre 4" => 4,
        ];
        $semestre = [
            "semestre 1" => 1,
            "semestre 2" => 2,
        ];
        $date = new \DateTime();
        $annee_max = intval($date->format('Y'));
        $liste_annee = [
            strval($annee_max) => $annee_max,
            strval($annee_max - 1) => $annee_max - 1,
            strval($annee_max - 2) => $annee_max - 2,
            strval($annee_max - 3) => $annee_max - 3,
            strval($annee_max - 4) => $annee_max - 4,
            strval($annee_max - 5) => $annee_max - 5,
            strval($annee_max - 6) => $annee_max - 6,
            strval($annee_max - 7) => $annee_max - 7,
            strval($annee_max - 8) => $annee_max - 8,
            strval($annee_max - 9) => $annee_max - 9,
            strval($annee_max - 10) => $annee_max - 10,
        ];
        $form_annuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
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
        $form_annuel->handleRequest($request);
        $form_semestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('semestre', ChoiceType::class, [
                'label' => 'Séléctionner le semestre',
                'choices' => $semestre,
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
        $form_semestriel->handleRequest($request);
        $form_trimestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('trimestre', ChoiceType::class, [
                'label' => 'Séléctionner le trimestre',
                'choices' => $trimestre,
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
        $form_trimestriel->handleRequest($request);
        $form_mensuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('mois', ChoiceType::class, [
                'label' => 'Séléctionner le mois',
                'choices' => $mois,
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
        $form_mensuel->handleRequest($request);

        return $this->render('ct_app_satistique/statistique_imprimer.html.twig', [
            'form_annuel' => $form_annuel->createView(),
            'form_semestriel' => $form_semestriel->createView(),
            'form_trimestriel' => $form_trimestriel->createView(),
            'form_mensuel' => $form_mensuel->createView(),
        ]);
    }

    /**
     * @Route("/statistique_autre_service", name="app_ct_app_statistique_statistique_autre_service")
     */
    public function StatistiqueAutreService(Request $request): Response
    {
        $mois = [
            "Janvier" => 1,
            "Février" => 2,
            "Mars" => 3,
            "Avril" => 4,
            "Mai" => 5,
            "Juin" => 6,
            "Juillet" => 7,
            "Août" => 8,
            "Septembre" => 9,
            "Octobre" => 10,
            "Novembre" => 11,
            "Décembre" => 12,
        ];
        $trimestre = [
            "trimestre 1" => 1,
            "trimestre 2" => 2,
            "trimestre 3" => 3,
            "trimestre 4" => 4,
        ];
        $semestre = [
            "semestre 1" => 1,
            "semestre 2" => 2,
        ];
        $date = new \DateTime();
        $annee_max = intval($date->format('Y'));
        $liste_annee = [
            strval($annee_max) => $annee_max,
            strval($annee_max - 1) => $annee_max - 1,
            strval($annee_max - 2) => $annee_max - 2,
            strval($annee_max - 3) => $annee_max - 3,
            strval($annee_max - 4) => $annee_max - 4,
            strval($annee_max - 5) => $annee_max - 5,
            strval($annee_max - 6) => $annee_max - 6,
            strval($annee_max - 7) => $annee_max - 7,
            strval($annee_max - 8) => $annee_max - 8,
            strval($annee_max - 9) => $annee_max - 9,
            strval($annee_max - 10) => $annee_max - 10,
        ];
        $form_annuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
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
        $form_annuel->handleRequest($request);
        $form_semestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('semestre', ChoiceType::class, [
                'label' => 'Séléctionner le semestre',
                'choices' => $semestre,
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
        $form_semestriel->handleRequest($request);
        $form_trimestriel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('trimestre', ChoiceType::class, [
                'label' => 'Séléctionner le trimestre',
                'choices' => $trimestre,
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
        $form_trimestriel->handleRequest($request);
        $form_mensuel = $this->createFormBuilder()
            ->add('annee', ChoiceType::class, [
                'label' => 'Séléctionner l\'année',
                'choices' => $liste_annee,
                'data' => $annee_max,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('mois', ChoiceType::class, [
                'label' => 'Séléctionner le mois',
                'choices' => $mois,
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
        $form_mensuel->handleRequest($request);

        return $this->render('ct_app_satistique/statistique_autre_service.html.twig', [
            'form_annuel' => $form_annuel->createView(),
            'form_semestriel' => $form_semestriel->createView(),
            'form_trimestriel' => $form_trimestriel->createView(),
            'form_mensuel' => $form_mensuel->createView(),
        ]);
    }
}
