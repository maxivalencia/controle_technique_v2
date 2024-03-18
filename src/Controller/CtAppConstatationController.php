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
use App\Form\CtConstatationEditType;
use App\Form\CtConstatationDisableType;
use App\Repository\CtConstAvDedTypeRepository;
use App\Repository\CtConstAvDedCaracRepository;
use App\Repository\CtConstAvDedRepository;
use App\Repository\CtUserRepository;
use App\Entity\CtImprimeTech;
use App\Repository\CtImprimeTechRepository;
use App\Repository\CtImprimeTechUseModulableRepository;
use App\Form\CtImprimeTechUseType;
use App\Form\CtImprimeTechUseModulableType;
use App\Entity\CtHistorique;
use App\Entity\CtHistoriqueType;
//use App\Form\CtHistoriqueType;
use App\Form\CtHistoriqueTypeType;
use App\Repository\CtHistoriqueRepository;
use App\Repository\CtHistoriqueTypeRepository;
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
use Doctrine\Common\Collections\ArrayCollection;

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
    public function CreerConstatationAvantDedouanement(Request $request, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
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
            /* ->add('ct_user_id', EntityType::class, [
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
            ]) */
            ->getForm();
        $form_fiche_controle->handleRequest($request);

        $form_constatation = $this->createForm(CtConstatationType::class, $ctConstatation, ["centre" => $this->getUser()->getCtCentreId()]);
        $form_constatation->handleRequest($request);

        if ($form_constatation->isSubmitted() && $form_constatation->isValid()) {
            // eto ny par note descriptive
            //$ctConstAvDedTypeRepository->findOneBy(["id" => 3]);
            $ctConstAvDedCarac_noteDescriptive->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_carrosserie_id']->getData());
            //$ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_note_descriptive']->getData());
            $ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId($ctConstAvDedTypeRepository->findOneBy(["id" => 3]));
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
            $ctConstAvDedCarac_noteDescriptive->setCadPoidsTotalCharge(floatval($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_vide']->getData()) + floatval($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_charge_utile']->getData()));
            $ctConstAvDedCarac_noteDescriptive->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_premiere_circule']->getData());
            $ctConstAvDedCarac_noteDescriptive->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_nbr_assis']->getData());
            //$ctConstAvDedCarac_noteDescriptive->addCtConstAvDed($form_constatation['unmapped_field']->getData());
            
            $ctConstAvDecCaracRepository->add($ctConstAvDedCarac_noteDescriptive, true);

            // eto ny par carte grise
            $ctConstAvDedCarac_carteGrise->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_carrosserie_id']->getData());
            //$ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_carte_grise']->getData());
            $ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId($ctConstAvDedTypeRepository->findOneBy(["id" => 1]));
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
            $ctConstAvDedCarac_carteGrise->setCadPoidsTotalCharge(floatval($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_vide']->getData()) + floatval($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_charge_utile']->getData()));
            $ctConstAvDedCarac_carteGrise->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_premiere_circule']->getData());
            $ctConstAvDedCarac_carteGrise->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_nbr_assis']->getData());
            //$ctConstAvDedCarac_carteGrise->addCtConstAvDed($form_constatation['unmapped_field']->getData());
            
            $ctConstAvDecCaracRepository->add($ctConstAvDedCarac_carteGrise, true);

            // eto ny par corps du véhicule
            $ctConstAvDedCarac_corpsDuVehicule->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_carrosserie_id']->getData());
            //$ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_corps_vehicule']->getData());
            $ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId($ctConstAvDedTypeRepository->findOneBy(["id" => 2]));
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
            $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsTotalCharge(floatval($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_vide']->getData()) + floatval($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_charge_utile']->getData()));
            $ctConstAvDedCarac_corpsDuVehicule->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_premiere_circule']->getData());
            $ctConstAvDedCarac_corpsDuVehicule->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_nbr_assis']->getData());
            //$ctConstAvDedCarac_corpsDuVehicule->addCtConstAvDed($form_constatation['unmapped_field']->getData());

            $ctConstAvDecCaracRepository->add($ctConstAvDedCarac_corpsDuVehicule, true);

            $ctConstatation_new->setCadCreated(new \DateTime());
            $ctConstatation_new->setCadIsActive(true);
            $ctConstatation_new->setCadGenere(0);
            $ctConstatation_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctConstatation_new->setCtUserId($this->getUser());
            $ctConstatation_new->setCtVerificateurId($ctConstatation->getCtVerificateurId());
            $ctConstatation_new->setCadProvenance($ctConstatation->getCadProvenance());
            $ctConstatation_new->setCadDivers($ctConstatation->getCadDivers());
            $ctConstatation_new->setCtUtilisationId($ctConstatation->getCtUtilisationId());
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
            $date = new \DateTime();
            $ctConstAvDedRepository->add($ctConstatation_new, true);
            $ctConstatation_new->setCadNumero($ctConstatation_new->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ctConstatation_new->getCtCentreId()->getCtrCode().'/'.'CONST/'.$date->format("Y"));
            $ctConstAvDedRepository->add($ctConstatation_new, true);

            if($ctConstatation->getId() != null && $ctConstatation->getId() < $ctConstatation_new->getId()){
                $ctConstatation->setCadIsActive(false);

                $ctConstAvDedRepository->add($ctConstatation, true);
            }

            $message = "Constatation ajouter avec succes";
            $enregistrement_ok = true;

            // asiana redirection mandeha amin'ny générer rehefa vita ilay izy
            //return $this->redirectToRoute('app_ct_app_constatation_voir_constatation_avant_dedouanement', ["id" => $ctConstatation_new->getId()], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_ct_app_imprime_technique_mise_a_jour_multiple', ["id" => $ctConstatation_new->getId()], Response::HTTP_SEE_OTHER);
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
        //$ctConstatations = $ctConstAvDedRepository->findBy(["ct_centre_id" => $this->getUser()->getCtCentreId(), "cad_created" => new \DateTime, "cad_is_active" => 1], ["id" => "DESC"]);
        $ctConstatations = $ctConstAvDedRepository->findByFicheDeControle($this->getUser()->getCtCentreId(), new \DateTime);
        $liste_des_constatations = new ArrayCollection();
        foreach($ctConstatations as $ctConstatation){
            $ctConst = [
                "id" => $ctConstatation->getId(),
                "cad_numero" => $ctConstatation->getCadNumero(),
                "cad_provenance" => $ctConstatation->getCadProvenance(),
                "cad_proprietaire_nom" => $ctConstatation->getCadProprietaireNom(),
                "cad_immatriculation" => $ctConstatation->getCadImmatriculation(),
            ];
            $liste_des_constatations->add($ctConst);
        }
        return $this->render('ct_app_constatation/liste_constatation.html.twig', [
            'ct_const_av_deds' => $liste_des_constatations,
            'total' => count($ctConstatations),
        ]);
    }

    /**
     * @Route("/duplicata_constatation_avant_dedouanement", name="app_ct_app_constatation_duplicata_constatation_avant_dedouanement", methods={"GET", "POST"})
     */
    public function DuplicataConstatationAvantDedouanement(Request $request, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        $ctConstatation = new CtConstAvDed();
        $ctConstatation_carac = new CtConstAvDedCarac();
        $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
        $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
        $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
        $message = "";
        $enregistrement_ok = False;
        $id = 0;

        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctConstatation = $ctConstAvDedRepository->findOneBy(["cad_immatriculation" => $recherche, "cad_is_active" => true], ["id" => "DESC"]);
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctConstatation = $ctConstAvDedRepository->findOneBy(["cad_immatriculation" => $recherche, "cad_is_active" => true], ["id" => "DESC"]);
            //var_dump($ctConstatation);
        }
        if($request->request->get('search-numero-serie')){
            $recherche = $request->request->get('search-numero-serie');
            $ctConstatation_carac = $ctConstAvDecCaracRepository->findOneBy(["cad_num_serie_type" => $recherche], ["id" => "DESC"]);
            if($ctConstatation_carac != null){
                $ctConstatation = $ctConstAvDedRepository->findOneBy(["ct_const_av_ded_carac" => $ctConstatation_carac, "cad_is_active" => true], ["id" => "DESC"]);
            }
        }
        if($request->query->get('search-numero-serie')){
            $recherche = $request->query->get('search-numero-serie');
            $ctConstatation_carac = $ctConstAvDecCaracRepository->findOneBy(["cad_num_serie_type" => $recherche], ["id" => "DESC"]);
            if($ctConstatation_carac != null){
                $ctConstatation = $ctConstAvDedRepository->findOneBy(["ct_const_av_ded_carac" => $ctConstatation_carac, "cad_is_active" => true], ["id" => "DESC"]);
            }
        }
        //$ctConstatation = $ctConstAvDedRepository->findOneBy(["cad_immatriculation" => "BW-589-TG"], ["id" => "DESC"]);
        if($ctConstatation != null){
            $id = $ctConstatation->getId();
            //$ctConstatation = new CtConstAvDed();
            //$ctConstatation = $ctConstAvDedRepository->findOneBy(["id" => $id], ["id" => "DESC"]);
            $ctConstAvDedCarac = $ctConstatation->getCtConstAvDedCarac();
            $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
            $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
            $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
            foreach($ctConstAvDedCarac as $ctCADC){
                if($ctCADC->getCtConstAvDedTypeId()->getId() == 1){
                    $ctConstAvDedCarac_carteGrise = $ctCADC;
                }
                if($ctCADC->getCtConstAvDedTypeId()->getId() == 2){
                    $ctConstAvDedCarac_corpsDuVehicule = $ctCADC;
                }
                if($ctCADC->getCtConstAvDedTypeId()->getId() == 3){
                    $ctConstAvDedCarac_noteDescriptive = $ctCADC;
                }
            }
        }

        return $this->render('ct_app_constatation/rechercher_constatation.html.twig', [
            'ct_const_av_ded' => $ctConstatation,
            'ct_const_av_ded_carac_notice_descriptive' => $ctConstAvDedCarac_noteDescriptive,
            'ct_const_av_ded_carac_carte_grise' => $ctConstAvDedCarac_carteGrise,
            'ct_const_av_ded_carac_corps_du_vehicule' => $ctConstAvDedCarac_corpsDuVehicule,
            'id' => $id,
        ]);
    }

    /**
     * @Route("/modification_constatation_avant_dedouanement/{id}", name="app_ct_app_constatation_modification_constatation_avant_dedouanement", methods={"GET", "POST"})
     */
    public function ModificationConstatationAvantDedouanement(Request $request, CtConstAvDed $ctConstatation, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        //$ctConstatation = new CtConstAvDed();
        $ctConstatation_new = new CtConstAvDed();
        $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
        $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
        $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
        if($request->request->get('search-immatriculation')){
            $recherche = strtoupper($request->request->get('search-immatriculation'));
            $ctConstatation = $ctConstAvDedRepository->findOneBy(["cad_immatriculation" => $recherche, "cad_is_active" => true], ["id" => "DESC"]);
        }
        if($request->query->get('search-immatriculation')){
            $recherche = strtoupper($request->query->get('search-immatriculation'));
            $ctConstatation = $ctConstAvDedRepository->findOneBy(["cad_immatriculation" => $recherche, "cad_is_active" => true], ["id" => "DESC"]);
            //var_dump($ctConstatation);
        }
        $identification_constatation = $ctConstatation->getId();
        $ctConstAvDedCarac = $ctConstatation->getCtConstAvDedCarac();
        foreach($ctConstAvDedCarac as $ctCADC){
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 1){
                $ctConstAvDedCarac_carteGrise = $ctCADC;
            }
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 2){
                $ctConstAvDedCarac_corpsDuVehicule = $ctCADC;
            }
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 3){
                $ctConstAvDedCarac_noteDescriptive = $ctCADC;
            }
        }

        $form_constatation = $this->createForm(CtConstatationEditType::class, $ctConstatation, ["centre" => $this->getUser()->getCtCentreId(), "carte_grise" => $ctConstAvDedCarac_carteGrise, "notice_descriptive" => $ctConstAvDedCarac_noteDescriptive, "corps_vehicule" => $ctConstAvDedCarac_corpsDuVehicule]);
        $form_constatation->handleRequest($request);

        if ($form_constatation->isSubmitted() && $form_constatation->isValid()) {
            // eto ny par note descriptive
            $ctConstAvDedCarac_noteDescriptive->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_note_descriptive']['ct_carrosserie_id']->getData());
            //$ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_note_descriptive']->getData());
            $ctConstAvDedCarac_noteDescriptive->setCtConstAvDedTypeId($ctConstAvDedTypeRepository->findOneBy(["id" => 3]));
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
            $ctConstAvDedCarac_noteDescriptive->setCadPoidsTotalCharge(floatval($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_poids_vide']->getData()) + floatval($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_charge_utile']->getData()));
            $ctConstAvDedCarac_noteDescriptive->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_premiere_circule']->getData());
            $ctConstAvDedCarac_noteDescriptive->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_note_descriptive']['cad_nbr_assis']->getData());
            //$ctConstAvDedCarac_noteDescriptive->addCtConstAvDed($form_constatation['unmapped_field']->getData());

            $ctConstAvDecCaracRepository->add($ctConstAvDedCarac_noteDescriptive, true);

            // eto ny par carte grise
            $ctConstAvDedCarac_carteGrise->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_carte_grise']['ct_carrosserie_id']->getData());
            //$ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_carte_grise']->getData());
            $ctConstAvDedCarac_carteGrise->setCtConstAvDedTypeId($ctConstAvDedTypeRepository->findOneBy(["id" => 1]));
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
            $ctConstAvDedCarac_carteGrise->setCadPoidsTotalCharge(floatval($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_poids_vide']->getData()) + floatval($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_charge_utile']->getData()));
            $ctConstAvDedCarac_carteGrise->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_premiere_circule']->getData());
            $ctConstAvDedCarac_carteGrise->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_carte_grise']['cad_nbr_assis']->getData());
            //$ctConstAvDedCarac_carteGrise->addCtConstAvDed($form_constatation['unmapped_field']->getData());

            $ctConstAvDecCaracRepository->add($ctConstAvDedCarac_carteGrise, true);

            // eto ny par corps du véhicule
            $ctConstAvDedCarac_corpsDuVehicule->setCtCarrosserieId($form_constatation['ct_const_av_ded_carac_corps_vehicule']['ct_carrosserie_id']->getData());
            //$ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId($form_constatation['ct_const_av_ded_carac_corps_vehicule']->getData());
            $ctConstAvDedCarac_corpsDuVehicule->setCtConstAvDedTypeId($ctConstAvDedTypeRepository->findOneBy(["id" => 2]));
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
            $ctConstAvDedCarac_corpsDuVehicule->setCadPoidsTotalCharge(floatval($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_poids_vide']->getData()) + floatval($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_charge_utile']->getData()));
            $ctConstAvDedCarac_corpsDuVehicule->setCadPremiereCircule($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_premiere_circule']->getData());
            $ctConstAvDedCarac_corpsDuVehicule->setCadNbrAssis($form_constatation['ct_const_av_ded_carac_corps_vehicule']['cad_nbr_assis']->getData());
            //$ctConstAvDedCarac_corpsDuVehicule->addCtConstAvDed($form_constatation['unmapped_field']->getData());

            $ctConstAvDecCaracRepository->add($ctConstAvDedCarac_corpsDuVehicule, true);

            $ctConstatation_new->setCadCreated(new \DateTime());
            $ctConstatation_new->setCadIsActive(true);
            $ctConstatation_new->setCadGenere($ctConstatation->getCadGenere());
            $ctConstatation_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctConstatation_new->setCtUserId($this->getUser());
            $ctConstatation_new->setCtVerificateurId($ctConstatation->getCtVerificateurId());
            $ctConstatation_new->setCadProvenance($ctConstatation->getCadProvenance());
            $ctConstatation_new->setCadDivers($ctConstatation->getCadDivers());
            $ctConstatation_new->setCtUtilisationId($ctConstatation->getCtUtilisationId());
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
            $ctConstatation_new->setCadNumero($ctConstatation_new->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$ctConstatation_new->getCtCentreId()->getCtrCode().'/'.'CONST/'.$date->format("Y"));
            $ctConstAvDedRepository->add($ctConstatation_new, true);

            /* if($ctConstatation->getId() != null && $ctConstatation->getId() < $ctConstatation_new->getId()){
                $ctConstatation->setCadIsActive(false);

                $ctConstAvDedRepository->add($ctConstatation, true);
            } */

            $message = "Constatation ajouter avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            return $this->redirectToRoute('app_ct_app_constatation_voir_constatation_avant_dedouanement_modification', ["id" => $ctConstatation_new->getId(), "old" => $ctConstatation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ct_app_constatation/modification_constatation.html.twig', [
            'form_constatation' => $form_constatation->createView(),
        ]);
    }

    /**
     * @Route("/voir_constatation_avant_dedouanement/{id}", name="app_ct_app_constatation_voir_constatation_avant_dedouanement", methods={"GET", "POST"})
     */
    public function VoirConstatationAvantDedouanement(Request $request, int $id, CtConstAvDed $ctConstatation, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        //$ctConstatation = new CtConstAvDed();
        $ctConstatation = $ctConstAvDedRepository->findOneBy(["id" => $id, "cad_is_active" => true], ["id" => "DESC"]);
        $ctConstAvDedCarac = $ctConstatation->getCtConstAvDedCarac();
        $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
        $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
        $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
        foreach($ctConstAvDedCarac as $ctCADC){
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 1){
                $ctConstAvDedCarac_carteGrise = $ctCADC;
            }
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 2){
                $ctConstAvDedCarac_corpsDuVehicule = $ctCADC;
            }
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 3){
                $ctConstAvDedCarac_noteDescriptive = $ctCADC;
            }
        }

        return $this->render('ct_app_constatation/recapitulation_constatation.html.twig', [
            'ct_const_av_ded' => $ctConstatation,
            'ct_const_av_ded_carac_notice_descriptive' => $ctConstAvDedCarac_noteDescriptive,
            'ct_const_av_ded_carac_carte_grise' => $ctConstAvDedCarac_carteGrise,
            'ct_const_av_ded_carac_corps_du_vehicule' => $ctConstAvDedCarac_corpsDuVehicule,
            'id' => $id,
        ]);
    }

    /**
     * @Route("/voir_constatation_avant_dedouanement_modification/{id}/{old}", name="app_ct_app_constatation_voir_constatation_avant_dedouanement_modification", methods={"GET", "POST"})
     */
    public function VoirConstatationAvantDedouanementModification(Request $request, int $id, int $old, CtConstAvDed $ctConstatation, CtConstAvDedRepository $ctConstAvDedRepository, CtConstAvDedCaracRepository $ctConstAvDecCaracRepository, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        //$ctConstatation = new CtConstAvDed();
        $ctConstatation = $ctConstAvDedRepository->findOneBy(["id" => $id, "cad_is_active" => true], ["id" => "DESC"]);
        $ctConstAvDedCarac = $ctConstatation->getCtConstAvDedCarac();
        $ctConstAvDedCarac_noteDescriptive = new CtConstAvDedCarac();
        $ctConstAvDedCarac_carteGrise = new CtConstAvDedCarac();
        $ctConstAvDedCarac_corpsDuVehicule = new CtConstAvDedCarac();
        $ctConstatation_old = $ctConstAvDedRepository->findOneBy(["id" => $old]);
        $ctConstatation_old->setCadIsActive(false);
        $ctConstAvDedRepository->add($ctConstatation_old, true);
        foreach($ctConstAvDedCarac as $ctCADC){
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 1){
                $ctConstAvDedCarac_carteGrise = $ctCADC;
            }
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 2){
                $ctConstAvDedCarac_corpsDuVehicule = $ctCADC;
            }
            if($ctCADC->getCtConstAvDedTypeId()->getId() == 3){
                $ctConstAvDedCarac_noteDescriptive = $ctCADC;
            }
        }

        return $this->render('ct_app_constatation/recapitulation_constatation.html.twig', [
            'ct_const_av_ded' => $ctConstatation,
            'ct_const_av_ded_carac_notice_descriptive' => $ctConstAvDedCarac_noteDescriptive,
            'ct_const_av_ded_carac_carte_grise' => $ctConstAvDedCarac_carteGrise,
            'ct_const_av_ded_carac_corps_du_vehicule' => $ctConstAvDedCarac_corpsDuVehicule,
            'id' => $id,
        ]);
    }
}
