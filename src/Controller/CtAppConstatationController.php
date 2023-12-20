<?php

namespace App\Controller;

use App\Entity\CtConstAvDedType;
use App\Entity\CtCentre;
use App\Entity\CtConstAvDed;
use App\Entity\CtUser;
use App\Form\CtConstAvDedTypeType;
use App\Form\CtConstatationCaracType;
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
    public function creerConstataionAvantDedouanement(Request $request, CtConstAvDedRepository $ctConstAvDedRepository): Response
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
            ->add('ct_const_av_ded_carac_note_descriptive', CtConstatationCaracType::class, [
                'label' => 'Note descriptive',
            ])
            ->add('ct_const_av_ded_carac_carte_grise', CtConstatationCaracType::class, [
                'label' => 'Carte grise',
            ])
            ->add('ct_const_av_ded_carac_corps_vehicule', CtConstatationCaracType::class, [
                'label' => 'Corp du véhicule',
            ])
            ->getForm();

            if ($form_constatation->isSubmitted() && $form_constatation->isValid()) {
                $ctConstAvDedRepository->add($ctConstatation, true);    

                $message = "Visite ajouter avec succes";
                $enregistrement_ok = true;
    
                // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            }

        return $this->render('ct_app_constatation/creer_constatation.html.twig', [
            'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
            'form_fiche_controle' => $form_fiche_controle->createView(),
            'form_constatation' => $form_constatation->createView(),
        ]);
    }
}
