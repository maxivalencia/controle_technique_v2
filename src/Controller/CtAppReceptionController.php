<?php

namespace App\Controller;

use App\Entity\CtCarrosserie;
use App\Entity\CtCentre;
use App\Entity\CtMotif;
use App\Entity\CtReception;
use App\Entity\CtSourceEnergie;
use App\Entity\CtTypeReception;
use App\Entity\CtVehicule;
use App\Entity\CtUser;
use App\Entity\CtUtilisation;
use App\Form\CtReceptionReceptionType;
use App\Form\CtReceptionVehiculeType;
use App\Form\CtTypeReceptionType;
use App\Repository\CtReceptionRepository;
use App\Repository\CtTypeReceptionRepository;
use App\Repository\CtVehiculeRepository;
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
use Symfony\Component\RateLimiter\Policy\Rate;

/**
 * @Route("/ct_app_reception")
 */
class CtAppReceptionController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_reception")
     */
    public function index(): Response
    {
        return $this->render('ct_app_reception/index.html.twig', [
            'controller_name' => 'CtAppReceptionController',
        ]);
    }
    
    /**
     * @Route("/liste_type", name="app_ct_app_reception_liste_type", methods={"GET"})
     */
    public function ListeType(CtTypeReceptionRepository $ctTypeReceptionRepository): Response
    {
        return $this->render('ct_app_reception/liste_type.html.twig', [
            'ct_type_receptions' => $ctTypeReceptionRepository->findAll(),
            'total' => count($ctTypeReceptionRepository->findAll()),
        ]);
    }
    
    /**
     * @Route("/creer_type", name="app_ct_app_reception_creer_type", methods={"GET", "POST"})
     */
    public function CreerType(Request $request, CtTypeReceptionRepository $ctTypeReceptionRepository): Response
    {
        $ctTypeReception = new CtTypeReception();
        $form = $this->createForm(CtTypeReceptionType::class, $ctTypeReception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeReceptionRepository->add($ctTypeReception, true);

            return $this->redirectToRoute('app_ct_app_reception_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_reception/creer_type.html.twig', [
            'ct_type_reception' => $ctTypeReception,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/voir_type/{id}", name="app_ct_app_reception_voir_type", methods={"GET"})
     */
    public function VoirType(CtTypeReception $ctTypeReception): Response
    {
        return $this->render('ct_app_reception/voir_type.html.twig', [
            'ct_type_reception' => $ctTypeReception,
        ]);
    }
    
    /**
     * @Route("/edit_type/{id}", name="app_ct_app_reception_edit_type", methods={"GET", "POST"})
     */
    public function EditType(Request $request, CtTypeReception $ctTypeReception, CtTypeReceptionRepository $ctTypeReceptionRepository): Response
    {
        $form = $this->createForm(CtTypeReceptionType::class, $ctTypeReception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeReceptionRepository->add($ctTypeReception, true);

            return $this->redirectToRoute('app_ct_app_reception_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_reception/edit_type.html.twig', [
            'ct_type_reception' => $ctTypeReception,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/del_type/{id}", name="app_ct_app_reception_del_type", methods={"GET", "POST"})
     */
    public function Delete(Request $request, CtTypeReception $ctTypeReception, CtTypeReceptionRepository $ctTypeReceptionRepository): Response
    {
        $ctTypeReceptionRepository->remove($ctTypeReception, true);

        return $this->redirectToRoute('app_ct_app_reception_liste_type', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/creer_reception", name="app_ct_app_reception_creer_reception", methods={"GET", "POST"})
     */
    public function CreerReception(Request $request): Response
    {
            
        $form_bilan = $this->createFormBuilder()
            ->add('ct_type_reception_id', EntityType::class, [
                'label' => 'Séléctionner le type de réception',
                'class' => CtTypeReception::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'multiple' => false,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Séléctionner la date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                    'style' => 'width:100%;',
                ],
                'data' => new \DateTime('now'),
            ])
            ->getForm();   
        $form_bilan->handleRequest($request);

        $form_nouvelle_reception = $this->createFormBuilder()
            ->add('ct_type_reception_id', EntityType::class, [
                'label' => 'Séléctionner le type de réception',
                'class' => CtTypeReception::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi reception_type',
                    'style' => 'width:100%;',
                    'multiple' => false,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->getForm();
        $form_nouvelle_reception->handleRequest($request);
        
        return $this->render('ct_app_reception/creer_reception.html.twig', [
            'form_bilan' => $form_bilan->createView(),
            'form_nouvelle_reception' => $form_nouvelle_reception->createView(),
        ]);
    }
    /**
     * @Route("/creer_reception_isole", name="app_ct_app_reception_creer_reception_isole", methods={"GET", "POST"})
     */
    public function CreerReceptionIsole(Request $request, CtReceptionRepository $ctReceptionRepository, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $ctReception = new CtReception();
        $ctVehicule = new CtVehicule();        
        $ctReception_new = new CtReception();

        //$form_reception = $this->createForm(CtReceptionReceptionType::class, $ct_reception);
        $form_reception = $this->createFormBuilder($ctReception)  
            ->add('ct_utilisation_id', EntityType::class, [
                'label' => 'Utilisation',
                'class' => CtUtilisation::class,
            ])
            ->add('ct_motif_id', EntityType::class, [
                'label' => 'Motif',
                'class' => CtMotif::class,
            ])
            ->add('rcp_immatriculation', TextType::class, [
                'label' => 'Immatriculation',
            ])
            ->add('rcp_proprietaire', TextType::class, [
                'label' => 'Propriétaire',
            ])
            ->add('rcp_profession', TextType::class, [
                'label' => 'Profession',
            ])
            ->add('rcp_adresse', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('rcp_nbr_assis', TextType::class, [
                'label' => 'Nombre de place assise',
                'data' => 0,
            ])
            ->add('rcp_ngr_debout', TextType::class, [
                'label' => 'Nombre de place debout',
                'data' => 0,
            ])
            ->add('ct_source_energie_id', EntityType::class, [
                'label' => 'Source d\'energie',
                'class' => CtSourceEnergie::class,
            ])
            ->add('ct_carrosserie_id', EntityType::class, [
                'label' => 'Carrosserie',
                'class' => CtCarrosserie::class,
            ])
            ->add('rcp_mise_service', DateType::class, [
                'label' => 'Date de mise en service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
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
            ->add('ct_vehicule_id', CtReceptionVehiculeType::class, [
                'label' => 'Véhicule',
            ])
        ->getForm();
        
        if ($form_reception->isSubmitted() && $form_reception->isValid()) {
            $ctVehicule->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctVehicule->setCtMarqueId($ctReception->getCtVehiculeId()->getCtMarqueId());
            $ctVehicule->setVhcCylindre($ctReception->getCtVehiculeId()->getVhcCylindre());
            $ctVehicule->setVhcPuissance($ctReception->getCtVehiculeId()->getVhcPuissance());
            $ctVehicule->setVhcPoidsVide($ctReception->getCtVehiculeId()->getVhcPoidsVide());
            $ctVehicule->setVhcChargeUtile($ctReception->getCtVehiculeId()->getVhcChargeUtile());
            $ctVehicule->setVhcNumSerie($ctReception->getCtVehiculeId()->getVhcNumSerie());
            $ctVehicule->setVhcNumMoteur($ctReception->getCtVehiculeId()->getVhcNumMoteur());
            $ctVehicule->setVhcCreated(new \DateTime());
            $ctVehicule->setVhcType($ctReception->getCtVehiculeId()->getVhcType());
            $ctVehicule->setVhcPoidsTotalCharge($ctReception->getCtVehiculeId()->getVhcPoidsVide() + $ctReception->getCtVehiculeId()->getVhcChargeUtile());

            $ctVehiculeRepository->add($ctVehicule, true);

            $ctReception_new->set
            /* return $this->render('ct_app_reception/creer_reception_isole.html.twig', [
            ]); */
        }
        return $this->render('ct_app_reception/creer_reception_isole.html.twig', [
            'form_reception' => $form_reception->createView(),
        ]);
    }
}
