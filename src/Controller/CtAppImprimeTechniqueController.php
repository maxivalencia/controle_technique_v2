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
 * @Route("/ct_app_imprime_technique")
 */
class CtAppImprimeTechniqueController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_imprime_technique")
     */
    public function index(): Response
    {
        return $this->render('ct_app_imprime_technique/index.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/activer_imprimer", name="app_ct_app_imprime_technique_activer_imprimer", methods={"GET", "POST"})
     */
    public function ActiverImprimer(Request $request, CtBordereauRepository $ctBordereauRepository, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $id_bordereau = "";
        $liste_bordereau_non_activer = new ArrayCollection();
        if($request->request->get('id')){
            $id_bordereau = $request->request->get('id');
        }
        if($request->query->get('id')){
            $id_bordereau = $request->query->get('id');
        }
        //$ct_imprimer_technique_is_activite = $ctImprimeTechUseRepository->findOneBy(["id" => $id_bordereau]);
        if($id_bordereau != ""){
            $bordereau = $ctBordereauRepository->findOneBy(["id" => $id_bordereau]);
            $debut = $bordereau->getBlDebutNumero();
            $fin = $bordereau->getBlFinNumero();
            $imprime = $bordereau->getCtImprimeTechId();
            $date = new \DateTime();
            $centre = $this->getUser()->getCtCentreId();
            $user = $this->getUser();
            for($id = $debut; $id <= $fin; $id++){
                $imprime_deja_utiliser = $ctImprimeTechUseRepository->findOneBy(["itu_numero" => $id, "ct_imprime_tech_id" => $imprime]);
                if($imprime_deja_utiliser == null){
                    $imprime_tech_use = new CtImprimeTechUse();
                    $imprime_tech_use->setCtBordereauId($bordereau);
                    $imprime_tech_use->setCtCentreId($centre);
                    $imprime_tech_use->setCtImprimeTechId($imprime);
                    $imprime_tech_use->setCtUserId($user);
                    $imprime_tech_use->setItuNumero($id);
                    $imprime_tech_use->setItuUsed(0);
                    $imprime_tech_use->setActivedAt($date);
                    $imprime_tech_use->setItuIsVisible(1);
                    //var_dump($imprime_tech_use);
                    $ctImprimeTechUseRepository->add($imprime_tech_use, true);
                }
            }
        }
        $ct_bordereaux = $ctBordereauRepository->findBy(["ct_centre_id" => $this->getUser()->getCtCentreId()], ["id" => "DESC"]);
        foreach($ct_bordereaux as $ct_bordereau){
            $ct_imprimer_technique_is_activite = $ctImprimeTechUseRepository->findOneBy(["ct_bordereau_id" => $ct_bordereau]);
            if($ct_imprimer_technique_is_activite == null){
                $liste_bordereau_non_activer->add($ct_bordereau);
            }
        }
        $total = count($liste_bordereau_non_activer);
        return $this->render('ct_app_imprime_technique/activer_imprimer.html.twig', [
            'ct_bordereaus' => $liste_bordereau_non_activer,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/liste_utiliser", name="app_ct_app_imprime_technique_liste_utiliser", methods={"GET", "POST"})
     */
    public function ListeUtiliser(Request $request, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $form_feuille_utilisation = $this->createFormBuilder()
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
        $form_feuille_utilisation->handleRequest($request);
        $form_situation_de_stock = $this->createFormBuilder()
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
        $form_situation_de_stock->handleRequest($request);

        $date = new \DateTime();
        $date_du_jour = $date->format("Y-m-d");
        $liste_imprimer_utiliser = $ctImprimeTechUseRepository->findBy(["ct_centre_id" => $this->getUser()->getCtCentreId(), "itu_used" => 1, "created_at" => $date]);
        return $this->render('ct_app_imprime_technique/liste_utiliser.html.twig', [
            'ct_imprime_tech_uses' => $liste_imprimer_utiliser ,
            'form_feuille_utilisation' => $form_feuille_utilisation->createView(),
            'form_situation_de_stock' => $form_situation_de_stock->createView(),
        ]);
    }

    /**
     * @Route("/liste_non_utiliser", name="app_ct_app_imprime_technique_liste_non_utiliser", methods={"GET", "POST"})
     */
    public function ListeNonUtiliser(Request $request, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $date = new \DateTime();
        $date_du_jour = $date->format("Y-m-d");
        $liste_imprimer_utiliser = $ctImprimeTechUseRepository->findBy(["ct_centre_id" => $this->getUser()->getCtCentreId(), "itu_used" => 1, "created_at" => $date]);
        return $this->render('ct_app_imprime_technique/liste_non_utiliser.html.twig', [
            'ct_imprime_tech_uses' => $liste_imprimer_utiliser ,
        ]);
    }

    /**
     * @Route("/mise_a_jour_utilisation", name="app_ct_app_imprime_technique_mise_a_jour_utilisation", methods={"GET", "POST"})
     */
    public function MiseAJourUtilisation(Request $request, CtImprimeTechRepository $ctImprimeTechRepository, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $id = 0;
        $ct_imprime_tech_use = new CtImprimeTechUse();
        $form_recherche = $this->createFormBuilder()
            ->add('numero_imprime_tech', TextType::class, [
                'label' => 'Par numéro d\'imprimé technique',
            ])
            ->add('ct_imprime_tech_id', EntityType::class, [
                'label' => 'Type d\'imprimé technique',
                'class' => CtImprimeTech::class,
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
        $form_recherche->handleRequest($request);

        if($form_recherche->isSubmitted() && $form_recherche->isValid()) {
            $imprime_technique_id = $form_recherche['ct_imprime_tech_id'];
            $numero_imprime_technique = 0;
            $num_imp_tech = $request->request->get('form');
            if($num_imp_tech['numero_imprime_tech'] != ""){
                $numero_imprime_technique = intval($num_imp_tech['numero_imprime_tech']);
                $imprime_technique_id = $num_imp_tech['ct_imprime_tech_id'];
            }

            $ct_imprime_tech_use = $ctImprimeTechUseRepository->findOneBy(["ct_imprime_tech_id" => $imprime_technique_id, "itu_numero" => $numero_imprime_technique, "itu_used" => 0, "ct_centre_id" => $this->getUser()->getCtCentreId()]);
            $id = $ct_imprime_tech_use->getId();
        }

        $form_imprime_tech_use = $this->createForm(CtImprimeTechUseDisableType::class, $ct_imprime_tech_use, ["disable" => true]);
        $form_imprime_tech_use->handleRequest($request);       

        return $this->render('ct_app_imprime_technique/mise_a_jour_utilisation.html.twig', [
            'form_recherche' => $form_recherche->createView(),
            'form_imprime_tech_use' => $form_imprime_tech_use->createView(),
            'id' => $id,
        ]);
    }

    /**
     * @Route("/mise_a_jour_utilisation_effectif/{id}", name="app_ct_app_imprime_technique_mise_a_jour_utilisation_effectif", methods={"GET", "POST"})
     */
    public function MiseAJourUtilisationEffectif(Request $request, int $id, CtImprimeTechUse $ctImprimeTechUse, CtImprimeTechRepository $ctImprimeTechRepository, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $ct_imprime_tech_use = $ctImprimeTechUseRepository->findOneBy(["id" => $id]);
        $form_imprime_tech_use = $this->createForm(CtImprimeTechUseDisableType::class, $ct_imprime_tech_use, ["disable" => false]);
        $form_imprime_tech_use->handleRequest($request);       

        if($form_imprime_tech_use->isSubmitted() && $form_imprime_tech_use->isValid()) {
            $ct_imprime_tech_use->setItuUsed(1);
            $ct_imprime_tech_use->setCreatedAt(new \DateTime());
            $ct_imprime_tech_use->setCtUserId($this->getUser());
            if($ct_imprime_tech_use->getCtUsageItId()->getUitLibelle() == "VISITE"){
                // Action raha visite dia makany makana ary ato anaty if no asiana ny redirection farany avy eo
                //$visite = 
            }
            //if($ct_imprime_tech_use->getCtControleId())
        }
        return $this->render('ct_app_imprime_technique/mise_a_jour_utilisation_2.html.twig', [
            'form_imprime_tech_use' => $form_imprime_tech_use->createView(),
        ]);
    }

    /**
     * @Route("/mise_a_jour_multiple", name="app_ct_app_imprime_technique_mise_a_jour_multiple", methods={"GET", "POST"})
     */
    public function MiseAJourMultiple(Request $request, CtImprimeTechRepository $ctImprimeTechRepository, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $ct_imprime_tech_use = new CtImprimeTechUse();
        //$ct_imprime_tech_use = $ctImprimeTechUseRepository->findOneBy(["id" => $id]);
        $form_imprime_tech_use = $this->createForm(CtImprimeTechUseMultipleType::class, $ct_imprime_tech_use);
        $form_imprime_tech_use->handleRequest($request);       

        if($form_imprime_tech_use->isSubmitted() && $form_imprime_tech_use->isValid()) {
            $ct_imprime_tech_use->setItuUsed(1);
            $ct_imprime_tech_use->setCreatedAt(new \DateTime());
            $ct_imprime_tech_use->setCtUserId($this->getUser());
            if($ct_imprime_tech_use->getCtUsageItId()->getUitLibelle() == "VISITE"){
                // Action raha visite dia makany makana ary ato anaty if no asiana ny redirection farany avy eo
                //$visite = 
            }
            //if($ct_imprime_tech_use->getCtControleId())
        }
        return $this->render('ct_app_imprime_technique/mise_a_jour_multiple.html.twig', [
            'form_imprime_tech_use' => $form_imprime_tech_use->createView(),
        ]);
    }

    /**
     * @Route("/liste_imprimer", name="app_ct_app_imprime_technique_liste_imprimer", methods={"GET", "POST"})
     */
    public function ListeImprimer(CtImprimeTechRepository $ctImprimeTechRepository): Response
    {
        return $this->render('ct_app_imprime_technique/liste_imprimer.html.twig', [
            'ct_imprime_teches' => $ctImprimeTechRepository->findAll(),
        ]);
    }

    /**
     * @Route("/creer_imprimer", name="app_ct_app_imprime_technique_creer_imprimer", methods={"GET", "POST"})
     */
    public function CreerImprimer(Request $request, CtImprimeTechRepository $ctImprimeTechRepository): Response
    {
        $ctImprimeTech = new CtImprimeTech();
        $form = $this->createForm(CtImprimeTechType::class, $ctImprimeTech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctImprimeTechRepository->add($ctImprimeTech, true);

            return $this->redirectToRoute('app_ct_app_imprime_technique_liste_imprimer', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ct_app_imprime_technique/creer_imprimer.html.twig', [
            'ct_imprime_tech' => $ctImprimeTech,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/rechercher_bordereau", name="app_ct_app_imprime_technique_rechercher_bordereau", methods={"GET", "POST"})
     */
    public function RechercherBordereau(Request $request, CtBordereauRepository $ctBordereauRepository): Response
    {
        $total = 0;
        $numero = "";
        if($request->request->get('numero')){
            $numero = $request->request->get('numero');
        }
        if($request->query->get('numero')){
            $numero = $request->query->get('numero');
        }
        $ct_bordereaux = $ctBordereauRepository->findBy(["bl_numero" => $numero], ["id" => "DESC"]);
        $total =  count($ct_bordereaux);
        return $this->render('ct_app_imprime_technique/recherche_bordereau.html.twig', [
            'ct_bordereaus' => $ct_bordereaux,
            'total' => $total,
            'numero' => $numero,
        ]);
    }

    /**
     * @Route("/liste_bordereau", name="app_ct_app_imprime_technique_liste_bordereau", methods={"GET", "POST"})
     */
    public function ListeBordereau(CtBordereauRepository $ctBordereauRepository): Response
    {
        //$ct_bordereaux = $ctBordereauRepository->findAll();
        $ct_bordereaux = $ctBordereauRepository->findBy([], ["id" => "DESC"]);
        $total = count($ct_bordereaux);
        return $this->render('ct_app_imprime_technique/liste_bordereau.html.twig', [
            'ct_bordereaus' => $ct_bordereaux,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/creer_bordereau", name="app_ct_app_imprime_technique_creer_bordereau", methods={"GET", "POST"})
     */
    public function CreerBordereau(Request $request, CtBordereauRepository $ctBordereauRepository): Response
    {
        $ctBordereau = new CtBordereau();
        $form = $this->createForm(CtBordereauAjoutType::class, $ctBordereau);
        $form->handleRequest($request);
        $numeroBordereau = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $ctBordereau_new = $ctBordereau;
            $ctBordereau_new->setCtUserId($this->getUser());
            $ctBordereau_new->setBlCreatedAt(new \DateTime());
            $ctBordereauRepository->add($ctBordereau, true);
            $numeroBordereau = $ctBordereau_new->getBlNumero();

            // eto no ametrahana ny filtre sao dia misy doublon ny imprimer angatahana amin'ny bordereau

            //return $this->redirectToRoute('app_ct_bordereau_index', [], Response::HTTP_SEE_OTHER);
        }
        $ct_bordereaux = $ctBordereauRepository->findBy(["bl_numero" => $numeroBordereau], ["id" => "DESC"]);
        $total =  count($ct_bordereaux);


        return $this->render('ct_app_imprime_technique/creer_bordereau.html.twig', [
            'ct_bordereau' => $ctBordereau,
            'form_bordereau' => $form->createView(),
            'numero' => $numeroBordereau,
            'ct_bordereaus' => $ct_bordereaux,
            'total' => $total,
        ]);
    }
}
