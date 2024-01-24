<?php

namespace App\Controller;

use App\Entity\CtConstAvDedType;
use App\Entity\CtCentre;
use App\Entity\CtConstAvDed;
use App\Entity\CtConstAvDedCarac;
use App\Entity\CtUser;
use App\Form\CtConstAvDedTypeType;
use App\Form\CtConstatationCaracType;
use App\Form\CtConstatationType;
use App\Repository\CtConstAvDedTypeRepository;
use App\Repository\CtConstAvDedCaracRepository;
use App\Repository\CtConstAvDedRepository;
use App\Repository\CtUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @Route("/ct_app_constatation")
 */
class CtAppConstatationController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_constatation")
     */
    public function index(): Response
    {
        return $this->render('ct_app_constatation/index.html.twig', [
            'controller_name' => 'CtAppConstatationController',
        ]);
    }

    /**
     * @Route("/liste_type", name="app_ct_app_constatation_liste_type", methods={"GET"})
     */
    public function ListeType(CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        return $this->render('ct_app_constatation/liste_type.html.twig', [
            'ct_const_av_ded_types' => $ctConstAvDedTypeRepository->findAll(),
            'total' => count($ctConstAvDedTypeRepository->findAll()),
        ]);
    }

    /**
     * @Route("/creer_type", name="app_ct_app_constatation_creer_type", methods={"GET", "POST"})
     */
    public function CreerType(Request $request, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        $ctConstAvDedType = new CtConstAvDedType();
        $form = $this->createForm(CtConstAvDedTypeType::class, $ctConstAvDedType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedTypeRepository->add($ctConstAvDedType, true);

            return $this->redirectToRoute('app_ct_app_constatation_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_constatation/creer_type.html.twig', [
            'ct_const_av_ded_type' => $ctConstAvDedType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/voir_type/{id}", name="app_ct_app_constatation_voir_type", methods={"GET"})
     */
    public function VoirType(CtConstAvDedType $ctConstAvDedType): Response
    {
        return $this->render('ct_app_constatation/voir_type.html.twig', [
            'ct_const_av_ded_type' => $ctConstAvDedType,
        ]);
    }

    /**
     * @Route("/edit_type/{id}", name="app_ct_app_constatation_edit_type", methods={"GET", "POST"})
     */
    public function EditType(Request $request, CtConstAvDedType $ctConstAvDedType, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        $form = $this->createForm(CtConstAvDedTypeType::class, $ctConstAvDedType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedTypeRepository->add($ctConstAvDedType, true);

            return $this->redirectToRoute('app_ct_app_constatation_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_constatation/edit_type.html.twig', [
            'ct_const_av_ded_type' => $ctConstAvDedType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/del_type/{id}", name="app_ct_app_constatation_del_type", methods={"GET", "POST"})
     */
    public function delete(Request $request, CtConstAvDedType $ctConstAvDedType, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        $ctConstAvDedTypeRepository->remove($ctConstAvDedType, true);

        return $this->redirectToRoute('app_ct_app_constatation_liste_type', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/creer_constatation_avant_dedouanement", name="app_ct_app_constatation_creer_constatation_avant_dedouanement", methods={"GET", "POST"})
     */
    public function CreerConstataionAvantDedouanement(Request $request, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository): Response
    {
        $etat = [
            'Oui' => true,
            'Non' => false
        ];
        $personne = [
            'Oui' => true,
            'Non' => false
        ];
        $marchandise = [
            'Oui' => true,
            'Non' => false
        ];
        $environnement = [
            'Oui' => true,
            'Non' => false
        ];
        $conforme = [
            'Oui' => true,
            'Non' => false
        ];
        $ctConstatation = new CtConstAvDed();
        $ctConstatation_new = new CtConstAvDed();
        $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
        $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
        $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
        $message = "";
        $enregistrement_ok = False;
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
        $form_feuille_de_caisse->handleRequest($request);

        $form_fiche_controle = $this->createFormBuilder()
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
                    'multiple' => false,
                    'style' => 'width:100%;',
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
                        ->setParameter('val1', 14)
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
        $form_fiche_controle->handleRequest($request);

        $form_constatation = $this->createFormBuilder($ctConstatation)
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
            ->add('cad_immatriculation', TextType::class, [
                'label' => 'Immatriculation',
            ])
            ->add('cad_provenance', TextType::class, [
                'label' => 'Provenance',
            ])
            ->add('cad_date_embarquement', DateType::class, [
                'label' => 'Data d\'embarquement',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cad_lieu_embarquement', TextType::class, [
                'label' => 'Lieu d\'embarquement',
            ])
            ->add('cad_proprietaire_nom', TextType::class, [
                'label' => 'Propriétaire',
            ])
            ->add('cad_proprietaire_adresse', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('cad_divers', TextType::class, [
                'label' => 'Divers',
            ])
            ->add('cad_observation', TextType::class, [
                'label' => 'Observation',
            ])
            ->add('cad_conforme', ChoiceType::class, [
                'label' => 'Est-conforme',
                'choices' => $conforme,
                'data' => false,
            ])
            ->add('cad_bon_etat', ChoiceType::class, [
                'label' => 'Bon état',
                'choices' => $etat,
                'data' => false,
            ])
            ->add('cad_sec_pers', ChoiceType::class, [
                'label' => 'Sécurité des personnes',
                'choices' => $personne,
                'data' => false,
            ])
            ->add('cad_sec_march', ChoiceType::class, [
                'label' => 'Sécurité des marchandises',
                'choices' => $marchandise,
                'data' => false,
            ])
            ->add('cad_protec_env', ChoiceType::class, [
                'label' => 'Protection de l\'environnement',
                'choices' => $environnement,
                'data' => false,
            ])
            /* ->add('ct_const_av_ded_carac_note_descriptive', new CtConstatationCaracType()) */
            ->add('ct_const_av_ded_carac_note_descriptive', CtConstatationCaracType::class, [
                'label' => 'Note descriptive',
                'mapped' => false,
            ])
            ->add('ct_const_av_ded_carac_carte_grise', CtConstatationCaracType::class, [
                'label' => 'Carte grise',
                'mapped' => false,
            ])
            ->add('ct_const_av_ded_carac_corps_vehicule', CtConstatationCaracType::class, [
                'label' => 'Corp du véhicule',
                'mapped' => false,
            ])
            ->getForm();

            if ($form_constatation->isSubmitted() && $form_constatation->isValid()) {
                // eto ny par note descriptive
                $ctConstAvDedCarac_noteDescriptive->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_note_descriptive']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId(1);
                $ctConstAvDedCarac_noteDescriptive->setCtGenreId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_genre_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtMarqueId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_marque_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadCylindre($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_cylindre']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPuissance($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_puissance']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadHauteur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_hauteur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadLargeur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_largeur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadLongueur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_longueur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadTypeCar($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_type_car']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_noteDescriptive->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                // eto ny par carte grise
                $ctConstAvDedCarac_carteGrise->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_carte_grise']->getData());
                $ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId(2);
                $ctConstAvDedCarac_carteGrise->setCtGenreId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_genre_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCtMarqueId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_marque_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCadCylindre($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_cylindre']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPuissance($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_puissance']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_carteGrise->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_carteGrise->setCadHauteur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_hauteur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadLargeur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_largeur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadLongueur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_longueur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadTypeCar($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_type_car']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_carteGrise->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                // eto ny par corps du véhicule
                $ctConstAvDedCarac_corpsDuVehicule->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_corps_vehicule']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId(3);
                $ctConstAvDedCarac_corpsDuVehicule->setCtGenreId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_genre_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtMarqueId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_marque_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadCylindre($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_cylindre']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPuissance($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_puissance']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadHauteur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_hauteur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadLargeur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_largeur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadLongueur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_longueur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadTypeCar($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_type_car']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_corpsDuVehicule->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                $ctConstatation_new->setCadCreated(new \DateTime());
                $ctConstatation_new->setCadIsActive(true);
                $ctConstatation_new->setCadGenere($ctConstatation->getCadGenere());
                $ctConstatation_new->setCtCentreId($this->getUser()->getCtCentreId());
                $ctConstatation_new->setCtUserId($$this->getUser());
                $ctConstatation_new->setCtVerificateurId($ctConstatation->getCtVerificateurId());
                $ctConstatation_new->setCadProvenance($ctConstatation->getCadProvenance());
                $ctConstatation_new->setCadDivers($ctConstatation->getCadDivers());
                $ctConstatation_new->setCadProprietaireNom($ctConstatation->getCadProprietaireNom());
                $ctConstatation_new->setCadProprietaireAdresse($ctConstatation->getCadProprietaireAdresse());
                $ctConstatation_new->setCadBonEtat($ctConstatation->isCadBonEtat());
                $ctConstatation_new->setCadSecPers($ctConstatation->isCadSecPers());
                $ctConstatation_new->setCadSecMarch($ctConstatation->isCadSecMarch());
                $ctConstatation_new->setCadProtecEnv($ctConstatation->isCadProtecEnv());
                $ctConstatation_new->setCadImmatriculation($ctConstatation->getCadImmatriculation());
                $ctConstatation_new->setCadDateEmbarquement($ctConstatation->getCadDateEmbarquement());
                $ctConstatation_new->setCadLieuEmbarquement($ctConstatation->getCadLieuEmbarquement());
                $ctConstatation_new->setCadObservation($ctConstatation->getCadObservation());
                $ctConstatation_new->setCadConforme($ctConstatation->isCadConforme());
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_noteDescriptive);
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_carteGrise);
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_corpsDuVehicule);
                //$ctConstatation_new->addCtConstAvDedCarac();

                $ctConstAvDedRepository->add($ctConstatation_new, true);
                $ctConstatation_new->setCadNumero($ctConstatation_new->getId().'/'.'CENSERO/'.$ctReception->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ctReception->getId().'CONST/'.date("Y"));
                $ctConstAvDedRepository->add($ctConstatation_new, true);

                if($ctConstatation->getId() != null && $ctConstatation->getId() < $ctConstatation_new->getId()){
                    $ctConstatation->setCadIsActive(false);

                    $ctConstAvDedRepository->add($ctRctConstatationeception, true);
                }

                $message = "Constatation ajouter avec succes";
                $enregistrement_ok = true;

                // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            }

        return $this->render('ct_app_constatation/creer_constatation.html.twig', [
            'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
            'form_fiche_controle' => $form_fiche_controle->createView(),
            'form_constatation' => $form_constatation->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
        ]);
    }

    /**
     * @Route("/liste_constatation_avant_dedouanement", name="app_ct_app_constatation_liste_constatation_avant_dedouanement", methods={"GET", "POST"})
     */
    public function ListeConstatationAvantDedouanement(CtConstAvDedRepository $ctConstAvDedRepository): Response
    {
        $ctConstatations = $ctConstAvDedRepository->findBy(["ct_centre_id" => $this->getUser()->getCtCentreId()], ["id" => "DESC"]);
        return $this->render('ct_app_constatation/liste_constatation.html.twig', [
            'ct_const_av_deds' => $ctConstatations,
            'total' => count($ctConstatations),
        ]);
    }

    /**
     * @Route("/modification_constatation_avant_dedouanement/{id}", name="app_ct_app_constatation_modification_constatation_avant_dedouanement", methods={"GET", "POST"})
     */
    public function ModificationConstataionAvantDedouanement(Request $request, CtConstAvDed $ctConstatation, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository): Response
    {
        $etat = [
            'Oui' => true,
            'Non' => false
        ];
        $personne = [
            'Oui' => true,
            'Non' => false
        ];
        $marchandise = [
            'Oui' => true,
            'Non' => false
        ];
        $environnement = [
            'Oui' => true,
            'Non' => false
        ];
        $conforme = [
            'Oui' => true,
            'Non' => false
        ];
        //$ctConstatation = new CtConstAvDed();
        $ctConstatation_new = new CtConstAvDed();
        $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
        $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
        $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
        $form_constatation = $this->createFormBuilder($ctConstatation)
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
            ->add('cad_immatriculation', TextType::class, [
                'label' => 'Immatriculation',
            ])
            ->add('cad_provenance', TextType::class, [
                'label' => 'Provenance',
            ])
            ->add('cad_date_embarquement', DateType::class, [
                'label' => 'Data d\'embarquement',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cad_lieu_embarquement', TextType::class, [
                'label' => 'Lieu d\'embarquement',
            ])
            ->add('cad_proprietaire_nom', TextType::class, [
                'label' => 'Propriétaire',
            ])
            ->add('cad_proprietaire_adresse', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('cad_divers', TextType::class, [
                'label' => 'Divers',
            ])
            ->add('cad_observation', TextType::class, [
                'label' => 'Observation',
            ])
            ->add('cad_conforme', ChoiceType::class, [
                'label' => 'Est-conforme',
                'choices' => $conforme,
                'data' => false,
            ])
            ->add('cad_bon_etat', ChoiceType::class, [
                'label' => 'Bon état',
                'choices' => $etat,
                'data' => false,
            ])
            ->add('cad_sec_pers', ChoiceType::class, [
                'label' => 'Sécurité des personnes',
                'choices' => $personne,
                'data' => false,
            ])
            ->add('cad_sec_march', ChoiceType::class, [
                'label' => 'Sécurité des marchandises',
                'choices' => $marchandise,
                'data' => false,
            ])
            ->add('cad_protec_env', ChoiceType::class, [
                'label' => 'Protection de l\'environnement',
                'choices' => $environnement,
                'data' => false,
            ])
            /* ->add('ct_const_av_ded_carac_note_descriptive', new CtConstatationCaracType()) */
            ->add('ct_const_av_ded_carac_note_descriptive', CtConstatationCaracType::class, [
                'label' => 'Note descriptive',
                'mapped' => false,
            ])
            ->add('ct_const_av_ded_carac_carte_grise', CtConstatationCaracType::class, [
                'label' => 'Carte grise',
                'mapped' => false,
            ])
            ->add('ct_const_av_ded_carac_corps_vehicule', CtConstatationCaracType::class, [
                'label' => 'Corp du véhicule',
                'mapped' => false,
            ])
            ->getForm();

            if ($form_constatation->isSubmitted() && $form_constatation->isValid()) {
                // eto ny par note descriptive
                $ctConstAvDedCarac_noteDescriptive->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_note_descriptive']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId(1);
                $ctConstAvDedCarac_noteDescriptive->setCtGenreId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_genre_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtMarqueId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_marque_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadCylindre($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_cylindre']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPuissance($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_puissance']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadHauteur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_hauteur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadLargeur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_largeur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadLongueur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_longueur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadTypeCar($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_type_car']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_noteDescriptive->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                // eto ny par carte grise
                $ctConstAvDedCarac_carteGrise->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_carte_grise']->getData());
                $ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId(2);
                $ctConstAvDedCarac_carteGrise->setCtGenreId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_genre_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCtMarqueId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_marque_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCadCylindre($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_cylindre']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPuissance($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_puissance']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_carteGrise->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_carteGrise->setCadHauteur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_hauteur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadLargeur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_largeur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadLongueur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_longueur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadTypeCar($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_type_car']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_carteGrise->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                // eto ny par corps du véhicule
                $ctConstAvDedCarac_corpsDuVehicule->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_corps_vehicule']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId(3);
                $ctConstAvDedCarac_corpsDuVehicule->setCtGenreId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_genre_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtMarqueId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_marque_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadCylindre($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_cylindre']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPuissance($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_puissance']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadHauteur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_hauteur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadLargeur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_largeur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadLongueur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_longueur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadTypeCar($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_type_car']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_corpsDuVehicule->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                $ctConstatation_new->setCadCreated(new \DateTime());
                $ctConstatation_new->setCadIsActive(true);
                $ctConstatation_new->setCadGenere($ctConstatation->getCadGenere());
                $ctConstatation_new->setCtCentreId($this->getUser()->getCtCentreId());
                $ctConstatation_new->setCtUserId($$this->getUser());
                $ctConstatation_new->setCtVerificateurId($ctConstatation->getCtVerificateurId());
                $ctConstatation_new->setCadProvenance($ctConstatation->getCadProvenance());
                $ctConstatation_new->setCadDivers($ctConstatation->getCadDivers());
                $ctConstatation_new->setCadProprietaireNom($ctConstatation->getCadProprietaireNom());
                $ctConstatation_new->setCadProprietaireAdresse($ctConstatation->getCadProprietaireAdresse());
                $ctConstatation_new->setCadBonEtat($ctConstatation->isCadBonEtat());
                $ctConstatation_new->setCadSecPers($ctConstatation->isCadSecPers());
                $ctConstatation_new->setCadSecMarch($ctConstatation->isCadSecMarch());
                $ctConstatation_new->setCadProtecEnv($ctConstatation->isCadProtecEnv());
                $ctConstatation_new->setCadImmatriculation($ctConstatation->getCadImmatriculation());
                $ctConstatation_new->setCadDateEmbarquement($ctConstatation->getCadDateEmbarquement());
                $ctConstatation_new->setCadLieuEmbarquement($ctConstatation->getCadLieuEmbarquement());
                $ctConstatation_new->setCadObservation($ctConstatation->getCadObservation());
                $ctConstatation_new->setCadConforme($ctConstatation->isCadConforme());
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_noteDescriptive);
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_carteGrise);
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_corpsDuVehicule);
                //$ctConstatation_new->addCtConstAvDedCarac();

                $ctConstAvDedRepository->add($ctConstatation_new, true);
                $ctConstatation_new->setCadNumero($ctConstatation_new->getId().'/'.'CENSERO/'.$ctReception->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ctReception->getId().'CONST/'.date("Y"));
                $ctConstAvDedRepository->add($ctConstatation_new, true);

                if($ctConstatation->getId() != null && $ctConstatation->getId() < $ctConstatation_new->getId()){
                    $ctConstatation->setCadIsActive(false);

                    $ctConstAvDedRepository->add($ctRctConstatationeception, true);
                }

                $message = "Constatation ajouter avec succes";
                $enregistrement_ok = true;

                // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            }

        return $this->render('ct_app_constatation/modification_constatation.html.twig', [
            'form_constatation' => $form_constatation->createView(),
        ]);
    }

    /**
     * @Route("/voir_constatation_avant_dedouanement/{id}", name="app_ct_app_constatation_voir_constatation_avant_dedouanement", methods={"GET", "POST"})
     */
    public function VoirConstataionAvantDedouanement(Request $request, CtConstAvDed $ctConstatation, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository): Response
    {
        $etat = [
            'Oui' => true,
            'Non' => false
        ];
        $personne = [
            'Oui' => true,
            'Non' => false
        ];
        $marchandise = [
            'Oui' => true,
            'Non' => false
        ];
        $environnement = [
            'Oui' => true,
            'Non' => false
        ];
        $conforme = [
            'Oui' => true,
            'Non' => false
        ];
        //$ctConstatation = new CtConstAvDed();
        $ctConstatation_new = new CtConstAvDed();
        $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
        $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
        $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
        $form_constatation = $this->createFormBuilder($ctConstatation)
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
                },
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'disabled' => true,
            ])
            ->add('cad_immatriculation', TextType::class, [
                'label' => 'Immatriculation',
                'disabled' => true,
            ])
            ->add('cad_provenance', TextType::class, [
                'label' => 'Provenance',
                'disabled' => true,
            ])
            ->add('cad_date_embarquement', DateType::class, [
                'label' => 'Data d\'embarquement',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
                'disabled' => true,
            ])
            ->add('cad_lieu_embarquement', TextType::class, [
                'label' => 'Lieu d\'embarquement',
                'disabled' => true,
            ])
            ->add('cad_proprietaire_nom', TextType::class, [
                'label' => 'Propriétaire',
                'disabled' => true,
            ])
            ->add('cad_proprietaire_adresse', TextType::class, [
                'label' => 'Adresse',
                'disabled' => true,
            ])
            ->add('cad_divers', TextType::class, [
                'label' => 'Divers',
                'disabled' => true,
            ])
            ->add('cad_observation', TextType::class, [
                'label' => 'Observation',
                'disabled' => true,
            ])
            ->add('cad_conforme', ChoiceType::class, [
                'label' => 'Est-conforme',
                'choices' => $conforme,
                'data' => false,
                'disabled' => true,
            ])
            ->add('cad_bon_etat', ChoiceType::class, [
                'label' => 'Bon état',
                'choices' => $etat,
                'data' => false,
                'disabled' => true,
            ])
            ->add('cad_sec_pers', ChoiceType::class, [
                'label' => 'Sécurité des personnes',
                'choices' => $personne,
                'data' => false,
                'disabled' => true,
            ])
            ->add('cad_sec_march', ChoiceType::class, [
                'label' => 'Sécurité des marchandises',
                'choices' => $marchandise,
                'data' => false,
                'disabled' => true,
            ])
            ->add('cad_protec_env', ChoiceType::class, [
                'label' => 'Protection de l\'environnement',
                'choices' => $environnement,
                'data' => false,
                'disabled' => true,
            ])
            /* ->add('ct_const_av_ded_carac_note_descriptive', new CtConstatationCaracType()) */
            ->add('ct_const_av_ded_carac_note_descriptive', CtConstatationCaracType::class, [
                'label' => 'Note descriptive',
                'mapped' => false,
                'disabled' => true,
            ])
            ->add('ct_const_av_ded_carac_carte_grise', CtConstatationCaracType::class, [
                'label' => 'Carte grise',
                'mapped' => false,
                'disabled' => true,
            ])
            ->add('ct_const_av_ded_carac_corps_vehicule', CtConstatationCaracType::class, [
                'label' => 'Corp du véhicule',
                'mapped' => false,
                'disabled' => true,
            ])
            ->getForm();

            if ($form_constatation->isSubmitted() && $form_constatation->isValid()) {
                // eto ny par note descriptive
                $ctConstAvDedCarac_noteDescriptive->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_note_descriptive']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId(1);
                $ctConstAvDedCarac_noteDescriptive->setCtGenreId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_genre_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtMarqueId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_marque_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadCylindre($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_cylindre']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPuissance($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_puissance']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadHauteur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_hauteur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadLargeur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_largeur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadLongueur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_longueur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadTypeCar($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_type_car']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_noteDescriptive->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_noteDescriptive->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                // eto ny par carte grise
                $ctConstAvDedCarac_carteGrise->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_carte_grise']->getData());
                $ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId(2);
                $ctConstAvDedCarac_carteGrise->setCtGenreId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_genre_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCtMarqueId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_marque_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_carteGrise->setCadCylindre($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_cylindre']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPuissance($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_puissance']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_carteGrise->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_carteGrise->setCadHauteur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_hauteur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadLargeur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_largeur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadLongueur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_longueur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_carteGrise->setCadTypeCar($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_type_car']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_carteGrise->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_carteGrise->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_carteGrise->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                // eto ny par corps du véhicule
                $ctConstAvDedCarac_corpsDuVehicule->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_carrosserie_id']->getData());
                //$ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_corps_vehicule']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId(3);
                $ctConstAvDedCarac_corpsDuVehicule->setCtGenreId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_genre_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtMarqueId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_marque_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCtSourceEnergieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_source_energie_id']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadCylindre($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_cylindre']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPuissance($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_puissance']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsVide($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_vide']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadChargeUtile($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_charge_utile']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadHauteur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_hauteur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadLargeur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_largeur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadLongueur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_longueur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNumSerieType($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_num_serie_type']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNumMoteur($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_num_moteur']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadTypeCar($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_type_car']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsTotalCharge($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_total_charge']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_premiere_circule']->getData());
                $ctConstAvDedCarac_corpsDuVehicule->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_nbr_assis']->getData());
                //$ctConstAvDedCarac_corpsDuVehicule->addCtConstAvDed($form_constatation['unmapped_field']->getData());

                $ctConstatation_new->setCadCreated(new \DateTime());
                $ctConstatation_new->setCadIsActive(true);
                $ctConstatation_new->setCadGenere($ctConstatation->getCadGenere());
                $ctConstatation_new->setCtCentreId($this->getUser()->getCtCentreId());
                $ctConstatation_new->setCtUserId($$this->getUser());
                $ctConstatation_new->setCtVerificateurId($ctConstatation->getCtVerificateurId());
                $ctConstatation_new->setCadProvenance($ctConstatation->getCadProvenance());
                $ctConstatation_new->setCadDivers($ctConstatation->getCadDivers());
                $ctConstatation_new->setCadProprietaireNom($ctConstatation->getCadProprietaireNom());
                $ctConstatation_new->setCadProprietaireAdresse($ctConstatation->getCadProprietaireAdresse());
                $ctConstatation_new->setCadBonEtat($ctConstatation->isCadBonEtat());
                $ctConstatation_new->setCadSecPers($ctConstatation->isCadSecPers());
                $ctConstatation_new->setCadSecMarch($ctConstatation->isCadSecMarch());
                $ctConstatation_new->setCadProtecEnv($ctConstatation->isCadProtecEnv());
                $ctConstatation_new->setCadImmatriculation($ctConstatation->getCadImmatriculation());
                $ctConstatation_new->setCadDateEmbarquement($ctConstatation->getCadDateEmbarquement());
                $ctConstatation_new->setCadLieuEmbarquement($ctConstatation->getCadLieuEmbarquement());
                $ctConstatation_new->setCadObservation($ctConstatation->getCadObservation());
                $ctConstatation_new->setCadConforme($ctConstatation->isCadConforme());
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_noteDescriptive);
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_carteGrise);
                $ctConstatation_new->addCtConstAvDedCarac($ctConstAvDedCarac_corpsDuVehicule);
                //$ctConstatation_new->addCtConstAvDedCarac();

                $ctConstAvDedRepository->add($ctConstatation_new, true);
                $ctConstatation_new->setCadNumero($ctConstatation_new->getId().'/'.'CENSERO/'.$ctReception->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ctReception->getId().'CONST/'.date("Y"));
                $ctConstAvDedRepository->add($ctConstatation_new, true);

                if($ctConstatation->getId() != null && $ctConstatation->getId() < $ctConstatation_new->getId()){
                    $ctConstatation->setCadIsActive(false);

                    $ctConstAvDedRepository->add($ctRctConstatationeception, true);
                }

                $message = "Constatation ajouter avec succes";
                $enregistrement_ok = true;

                // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            }

        return $this->render('ct_app_constatation/voir_constatation.html.twig', [
            'form_constatation' => $form_constatation->createView(),
        ]);
    }
}
