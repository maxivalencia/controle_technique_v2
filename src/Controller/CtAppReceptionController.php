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
use App\Form\CtReceptionReceptionDisableType;
use App\Form\CtReceptionVehiculeType;
use App\Form\CtTypeReceptionType;
use App\Repository\CtReceptionRepository;
use App\Repository\CtTypeReceptionRepository;
use App\Repository\CtVehiculeRepository;
use App\Repository\CtUserRepository;
use App\Repository\CtRoleRepository;
use App\Entity\CtImprimeTech;
use App\Repository\CtImprimeTechRepository;
use App\Repository\CtImprimeTechUseRepository;
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
use Symfony\Component\RateLimiter\Policy\Rate;
use Doctrine\Common\Collections\ArrayCollection;

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
                    'data' => 2,
                ],
                'required' => false,
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
            ->add('ct_centre_id', EntityType::class, [
                'label' => 'Séléctionner le centre',
                'class' => CtCentre::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'style' => 'width:100%;',
                    'multiple' => false,
                    'data-live-search' => true,
                    'data-select' => false,
                    'data' => '',
                    //'data' => $this->getUser()->getCtCentreId(),
                ],
                'required' => false,
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
                    'data' => 1,
                ],
                'required' => false,
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
    public function CreerReceptionIsole(Request $request, CtTypeReceptionRepository $ctTypeReceptionRepository, CtUserRepository $ctUserRepository, CtRoleRepository $ctRoleRepository, CtReceptionRepository $ctReceptionRepository, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $ctReception = new CtReception();
        $ctVehicule = new CtVehicule();
        $ctReception_new = new CtReception();
        $message = "";
        $enregistrement_ok = False;
        $typeReception = $ctTypeReceptionRepository->findOneBy(["id" => 2]);

        $form_reception = $this->createForm(CtReceptionReceptionType::class, $ctReception, ['centre' => $this->getUser()->getCtCentreId()]);
        $form_reception->handleRequest($request);

        //if ($form_reception->isSubmitted() && $form_reception->isValid()) {
        if ($form_reception->isSubmitted() && $form_reception->isValid()) {
            $ctVehicule->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctVehicule->setCtMarqueId($ctReception->getCtVehiculeId()->getCtMarqueId());
            $ctVehicule->setVhcCylindre($ctReception->getCtVehiculeId()->getVhcCylindre());
            $ctVehicule->setVhcPuissance($ctReception->getCtVehiculeId()->getVhcPuissance());
            $ctVehicule->setVhcPoidsVide($ctReception->getCtVehiculeId()->getVhcPoidsVide());
            $ctVehicule->setVhcChargeUtile($ctReception->getCtVehiculeId()->getVhcChargeUtile());
            $ctVehicule->setVhcNumSerie($ctReception->getCtVehiculeId()->getVhcNumSerie());
            $ctVehicule->setVhcNumMoteur($ctReception->getCtVehiculeId()->getVhcNumMoteur());
            $ctVehicule->setVhcLongueur($ctReception->getCtVehiculeId()->getVhcLongueur());
            $ctVehicule->setVhcLargeur($ctReception->getCtVehiculeId()->getVhcLargeur());
            $ctVehicule->setVhcHauteur($ctReception->getCtVehiculeId()->getVhcHauteur());
            $ctVehicule->setVhcCreated(new \DateTime());
            $ctVehicule->setVhcType($ctReception->getCtVehiculeId()->getVhcType());
            $ctVehicule->setVhcPoidsTotalCharge($ctReception->getCtVehiculeId()->getVhcPoidsVide() + $ctReception->getCtVehiculeId()->getVhcChargeUtile());

            $ctVehiculeRepository->add($ctVehicule, true);

            $ctReception_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctReception_new->setCtMotifId($ctReception->getCtMotifId());
            $ctReception_new->setCtTypeReceptionId($typeReception);
            $ctReception_new->setCtUserId($this->getUser());
            $ctReception_new->setCtVerificateurId($ctReception->getCtVerificateurId());
            $ctReception_new->setCtUtilisationId($ctReception->getCtUtilisationId());
            $ctReception_new->setCtVehiculeId($ctVehicule);
            $ctReception_new->setRcpMiseService($ctReception->getRcpMiseService());
            $ctReception_new->setRcpImmatriculation($ctReception->getRcpImmatriculation());
            $ctReception_new->setRcpProprietaire($ctReception->getRcpProprietaire());
            $ctReception_new->setRcpProfession($ctReception->getRcpProfession());
            $ctReception_new->setRcpAdresse($ctReception->getRcpAdresse());
            $ctReception_new->setRcpNbrAssis($ctReception->getRcpNbrAssis());
            $ctReception_new->setRcpNgrDebout($ctReception->getRcpNgrDebout());
            $ctReception_new->setCtSourceEnergieId($ctReception->getCtSourceEnergieId());
            $ctReception_new->setCtCarrosserieId($ctReception->getCtCarrosserieId());
            $date = new \DateTime();
            //$date = $date->format('Y-m-d');
            //$ctReception_new->setRcpNumGroup($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$typeReception->getTprcpLibelle().'/'.date("Y"));
            $ctReception_new->setRcpNumGroup("");
            $ctReception_new->setRcpCreated(new \DateTime());
            $ctReception_new->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctReception_new->setRcpIsActive(true);
            $ctReception_new->setRcpGenere(intval($ctReception->getRcpGenere()));
            $ctReception_new->setRcpObservation("");

            $ctReceptionRepository->add($ctReception_new, true);
            $ctReception_new->setRcpNumPv($ctReception_new->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.'RECEP/'.$date->format("Y"));
            $ctReceptionRepository->add($ctReception_new, true);

            if($ctReception->getId() != null && $ctReception->getId() < $ctReception_new->getId()){
                $ctReception->setRcpIsActive(false);

                $ctReceptionRepository->add($ctReception, true);
            }

            $message = "Réception ajouter avec succes";
            $enregistrement_ok = true;

            //return $this->redirectToRoute('app_ct_app_reception_recapitulation_reception_isole', ["id" => $ctReception_new->getId()]);
            return $this->redirectToRoute('app_ct_app_imprime_technique_mise_a_jour_multiple', ["id" => $ctReception_new->getId()]);
            
        }
        return $this->render('ct_app_reception/creer_reception_isole.html.twig', [
            'form_reception' => $form_reception->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
        ]);
    }

    /**
     * @Route("/feuille_de_caisse", name="app_ct_app_reception_feuille_de_caisse", methods={"GET", "POST"})
     */
    public function FeuilleDeCaisse(): Response
    {
        //ao anaty CtAppImprimable no misy ny fonction
        return $this->render('ct_app_reception/index.html.twig', [
            'controller_name' => 'CtAppReceptionController',
        ]);
    }

    /**
     * @Route("/fiche_de_controle", name="app_ct_app_reception_fiche_de_controle", methods={"GET", "POST"})
     */
    public function FicheDeControle(): Response
    {
        //ao anaty CtAppImprimable no misy ny fonction
        return $this->render('ct_app_reception/index.html.twig', [
            'controller_name' => 'CtAppReceptionController',
        ]);
    }

    /**
     * @Route("/creer_reception_par_type", name="app_ct_app_reception_creer_reception_par_type", methods={"GET", "POST"})
     */
    public function CreerReceptionParType(Request $request, CtTypeReceptionRepository $ctTypeReceptionRepository, CtRoleRepository $ctRoleRepository, CtReceptionRepository $ctReceptionRepository, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $ctReception = new CtReception();
        $ctVehicule = new CtVehicule();
        $ctReception_new = new CtReception();
        $message = "";
        $enregistrement_ok = False;
        $vehicule_encours = 1;
        $total_vehicule = 0;
        $typeReception = $ctTypeReceptionRepository->findOneBy(["id" => 1]);
        $date = new \DateTime();
        $code = '';

        //$code = $ctReception->getRcpNumGroup();
        $code = $request->request->get('code');
        if($code == ''){
            $code = $date->format('H_i_s_d_m_Y').'_'.$this->getUser()->getCtCentreId()->getCtrCode().'_'.$this->getUser()->getId();
            $ctReception->setRcpNumGroup($code);
        }else{
            $ctReception->setRcpNumGroup($code);
        }

        $form_reception = $this->createForm(CtReceptionReceptionType::class, $ctReception, ['centre' => $this->getUser()->getCtCentreId()]);
        $form_reception->handleRequest($request);

        if ($form_reception->isSubmitted() && $form_reception->isValid()) {
            $ctVehicule->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctVehicule->setCtMarqueId($ctReception->getCtVehiculeId()->getCtMarqueId());
            $ctVehicule->setVhcCylindre($ctReception->getCtVehiculeId()->getVhcCylindre());
            $ctVehicule->setVhcPuissance($ctReception->getCtVehiculeId()->getVhcPuissance());
            $ctVehicule->setVhcPoidsVide($ctReception->getCtVehiculeId()->getVhcPoidsVide());
            $ctVehicule->setVhcChargeUtile($ctReception->getCtVehiculeId()->getVhcChargeUtile());
            $ctVehicule->setVhcNumSerie($ctReception->getCtVehiculeId()->getVhcNumSerie());
            $ctVehicule->setVhcNumMoteur($ctReception->getCtVehiculeId()->getVhcNumMoteur());
            $ctVehicule->setVhcLongueur($ctReception->getCtVehiculeId()->getVhcLongueur());
            $ctVehicule->setVhcLargeur($ctReception->getCtVehiculeId()->getVhcLargeur());
            $ctVehicule->setVhcHauteur($ctReception->getCtVehiculeId()->getVhcHauteur());
            $ctVehicule->setVhcCreated(new \DateTime());
            $ctVehicule->setVhcType($ctReception->getCtVehiculeId()->getVhcType());
            $ctVehicule->setVhcPoidsTotalCharge($ctReception->getCtVehiculeId()->getVhcPoidsVide() + $ctReception->getCtVehiculeId()->getVhcChargeUtile());

            $ctVehiculeRepository->add($ctVehicule, true);

            $ctReception_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctReception_new->setCtMotifId($ctReception->getCtMotifId());
            $ctReception_new->setCtTypeReceptionId($typeReception);
            $ctReception_new->setCtUserId($this->getUser());
            $ctReception_new->setCtVerificateurId($ctReception->getCtVerificateurId());
            $ctReception_new->setCtUtilisationId($ctReception->getCtUtilisationId());
            $ctReception_new->setCtVehiculeId($ctVehicule);
            $ctReception_new->setRcpMiseService($ctReception->getRcpMiseService());
            $ctReception_new->setRcpImmatriculation($ctReception->getRcpImmatriculation());
            $ctReception_new->setRcpProprietaire($ctReception->getRcpProprietaire());
            $ctReception_new->setRcpProfession($ctReception->getRcpProfession());
            $ctReception_new->setRcpAdresse($ctReception->getRcpAdresse());
            $ctReception_new->setRcpNbrAssis($ctReception->getRcpNbrAssis());
            $ctReception_new->setRcpNgrDebout($ctReception->getRcpNgrDebout());
            $ctReception_new->setCtSourceEnergieId($ctReception->getCtSourceEnergieId());
            $ctReception_new->setCtCarrosserieId($ctReception->getCtCarrosserieId());
            $date = new \DateTime();
            //$date = $date->format('Y-m-d');
            //$ctReception_new->setRcpNumGroup($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$typeReception->getTprcpLibelle().'/'.$date->format("Y"));
            $ctReception_new->setRcpNumGroup($code);
            $ctReception_new->setRcpCreated(new \DateTime());
            $ctReception_new->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctReception_new->setRcpIsActive(true);
            $ctReception_new->setRcpGenere(intval($ctReception->getRcpGenere()));
            $ctReception_new->setRcpObservation("");

            $ctReceptionRepository->add($ctReception_new, true);
            $ctReception_new->setRcpNumPv($ctReception_new->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.'RECEP/'.$date->format("Y"));
            $ctReceptionRepository->add($ctReception_new, true);

            if($ctReception->getId() != null && $ctReception->getId() < $ctReception_new->getId()){
                $ctReception->setRcpIsActive(false);

                $ctReceptionRepository->add($ctReception, true);
            }
            $code = $ctReception_new->getRcpNumGroup();
            $message = "Réception ajouter avec succes";
            $enregistrement_ok = true;
            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
        }

        if($request->request->get('vehicule_encours')){
            $total_vehicule = (int)$request->request->get('total_vehicule');
            $vehicule_encours = (int)$request->request->get('vehicule_encours') + 1;
            //$code = $request->request->get('code');
            if($vehicule_encours > $total_vehicule){
                // eto no assiana ny redirection rehefa vita ny boucle rehetra
                //return $this->redirectToRoute('app_ct_app_reception_recapitulation_reception_par_type', ["id" => $code]);
                return $this->redirectToRoute('app_ct_app_imprime_technique_mise_a_jour_multiple', ["id" => $code]);
            }
        }
        if($request->request->get('total_vehicule')){
            $total_vehicule = (int)$request->request->get('total_vehicule');
        }
        if($request->query->get('vehicule_encours')){
            $total_vehicule = (int)$request->query->get('total_vehicule');
            $vehicule_encours = (int)$request->query->get('vehicule_encours') + 1;
            //$code = $request->query->get('code');
            if($vehicule_encours > $total_vehicule){
                // eto no assiana ny redirection rehefa vita ny boucle rehetra
                //return $this->redirectToRoute('app_ct_app_reception_recapitulation_reception_par_type', ["id" => $code]);
                return $this->redirectToRoute('app_ct_app_imprime_technique_mise_a_jour_multiple', ["id" => $code]);
            }
        }
        if($request->query->get('total_vehicule')){
            $total_vehicule = (int)$request->query->get('total_vehicule');
        }

        if($request->request->get('nombre_vehicule')){
            $total_vehicule = (int)$request->request->get('nombre_vehicule');
        }
        if($request->query->get('nombre_vehicule')){
            $total_vehicule = (int)$request->query->get('nombre_vehicule');
        }

        return $this->render('ct_app_reception/creer_reception_par_type.html.twig', [
            'form_reception' => $form_reception->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            'total_vehicule' => $total_vehicule,
            'vehicule_encours' => $vehicule_encours,
            'code' => $code,
        ]);
    }

    /**
     * @Route("/recherche_reception_duplicata", name="app_ct_app_reception_recherche_reception_duplicata", methods={"GET", "POST"})
     */
    public function RechercheReceptionDuplicata(Request $request, CtTypeReceptionRepository $ctTypeReceptionRepository, CtRoleRepository $ctRoleRepository, CtReceptionRepository $ctReceptionRepository, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $ctReception = new CtReception();
        $ctVehicule = new CtVehicule();
        $ctReception_new = new CtReception();
        $message = "";
        $enregistrement_ok = False;

        if($request->request->get('search-immatriculation')){
            $recherche = $request->request->get('search-immatriculation');
            $ctReception = $ctReceptionRepository->findOneBy(["rcp_immatriculation" => $recherche, "rcp_is_active" => true], ["id" => "DESC"]);
        }
        if($request->query->get('search-immatriculation')){
            $recherche = $request->query->get('search-immatriculation');
            $ctReception = $ctReceptionRepository->findOneBy(["rcp_immatriculation" => $recherche, "rcp_is_active" => true], ["id" => "DESC"]);
        }
        if($request->request->get('search-numero-serie')){
            $recherche = $request->request->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctReception = $ctReceptionRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "rcp_is_active" => true], ["id" => "DESC"]);
            }
        }
        if($request->query->get('search-numero-serie')){
            $recherche = $request->query->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctReception = $ctReceptionRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "rcp_is_active" => true], ["id" => "DESC"]);
            }
        }

        $form_reception = $this->createForm(CtReceptionReceptionDisableType::class, $ctReception, ["disabled" => true]);
        $form_reception->handleRequest($request);

        if ($form_reception->isSubmitted() && $form_reception->isValid()) {
            $ctVehicule->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctVehicule->setCtMarqueId($ctReception->getCtVehiculeId()->getCtMarqueId());
            $ctVehicule->setVhcCylindre($ctReception->getCtVehiculeId()->getVhcCylindre());
            $ctVehicule->setVhcPuissance($ctReception->getCtVehiculeId()->getVhcPuissance());
            $ctVehicule->setVhcPoidsVide($ctReception->getCtVehiculeId()->getVhcPoidsVide());
            $ctVehicule->setVhcChargeUtile($ctReception->getCtVehiculeId()->getVhcChargeUtile());
            $ctVehicule->setVhcNumSerie($ctReception->getCtVehiculeId()->getVhcNumSerie());
            $ctVehicule->setVhcNumMoteur($ctReception->getCtVehiculeId()->getVhcNumMoteur());
            $ctVehicule->setVhcLongueur($ctReception->getCtVehiculeId()->getVhcLongueur());
            $ctVehicule->setVhcLargeur($ctReception->getCtVehiculeId()->getVhcLargeur());
            $ctVehicule->setVhcHauteur($ctReception->getCtVehiculeId()->getVhcHauteur());
            $ctVehicule->setVhcCreated(new \DateTime());
            $ctVehicule->setVhcType($ctReception->getCtVehiculeId()->getVhcType());
            $ctVehicule->setVhcPoidsTotalCharge($ctReception->getCtVehiculeId()->getVhcPoidsVide() + $ctReception->getCtVehiculeId()->getVhcChargeUtile());

            $ctVehiculeRepository->add($ctVehicule, true);

            $ctReception_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctReception_new->setCtMotifId($ctReception->getCtMotifId());
            $ctReception_new->setCtTypeReceptionId($ctReception->getCtTypeReceptionId());
            $ctReception_new->setCtUserId($this->getUser());
            $ctReception_new->setCtVerificateurId($ctReception->getCtVerificateurId());
            $ctReception_new->setCtUtilisationId($ctReception->getCtUtilisationId());
            $ctReception_new->setCtVehiculeId($ctVehicule);
            $ctReception_new->setRcpMiseService($ctReception->getRcpMiseService());
            $ctReception_new->setRcpImmatriculation($ctReception->getRcpImmatriculation());
            $ctReception_new->setRcpProprietaire($ctReception->getRcpProprietaire());
            $ctReception_new->setRcpProfession($ctReception->getRcpProfession());
            $ctReception_new->setRcpAdresse($ctReception->getRcpAdresse());
            $ctReception_new->setRcpNbrAssis($ctReception->getRcpNbrAssis());
            $ctReception_new->setRcpNgrDebout($ctReception->getRcpNgrDebout());
            $ctReception_new->setCtSourceEnergieId($ctReception->getCtSourceEnergieId());
            $ctReception_new->setCtCarrosserieId($ctReception->getCtCarrosserieId());
            $date = new \DateTime();
            //$date = $date->format('Y-m-d');
            //$ctReception_new->setRcpNumGroup($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctReception->getCtTypeReceptionId()->getTprcpLibelle().'/'.$date->format("Y"));
            $ctReception_new->setRcpNumGroup($ctReception->getRcpNumGroup());
            $ctReception_new->setRcpCreated(new \DateTime());
            $ctReception_new->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctReception_new->setRcpIsActive(true);
            $ctReception_new->setRcpGenere(intval($ctReception->getRcpGenere()));
            $ctReception_new->setRcpObservation($ctReception->getRcpObservation()." ");

            $ctReceptionRepository->add($ctReception_new, true);
            $ctReception_new->setRcpNumPv($ctReception_new->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.'RECEP/'.$date->format("Y"));
            $ctReceptionRepository->add($ctReception_new, true);

            $message = "Réception ajouter avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
        }
        return $this->render('ct_app_reception/duplicata_reception.html.twig', [
            'form_reception' => $form_reception->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            'id' => $ctReception->getId(),
        ]);
    }

    /**
     * @Route("/recherche_reception_modification", name="app_ct_app_reception_recherche_reception_modification", methods={"GET", "POST"})
     */
    public function RechercheReceptionModification(Request $request, CtTypeReceptionRepository $ctTypeReceptionRepository, CtRoleRepository $ctRoleRepository, CtReceptionRepository $ctReceptionRepository, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $ctReception = new CtReception();
        $ctVehicule = new CtVehicule();
        $ctReception_new = new CtReception();
        $message = "";
        $enregistrement_ok = False;

        if($request->request->get('search-immatriculation')){
            $recherche = $request->request->get('search-immatriculation');
            $ctReception = $ctReceptionRepository->findOneBy(["rcp_immatriculation" => $recherche, "rcp_is_active" => true], ["id" => "DESC"]);
        }
        if($request->query->get('search-immatriculation')){
            $recherche = $request->query->get('search-immatriculation');
            $ctReception = $ctReceptionRepository->findOneBy(["rcp_immatriculation" => $recherche, "rcp_is_active" => true], ["id" => "DESC"]);
        }
        if($request->query->get('search-numero-serie')){
            $recherche = $request->query->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctReception = $ctReceptionRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "rcp_is_active" => true], ["id" => "DESC"]);
            }
        }
        if($request->request->get('search-numero-serie')){
            $recherche = $request->request->get('search-numero-serie');
            $vehicule_id = $ctVehiculeRepository->findOneBy(["vhc_num_serie" => $recherche], ["id" => "DESC"]);
            if($vehicule_id != null){
                $ctReception = $ctReceptionRepository->findOneBy(["ct_vehicule_id" => $vehicule_id, "rcp_is_active" => true], ["id" => "DESC"]);
            }
        }

        //$form_reception = $this->createForm(CtReceptionReceptionType::class, $ctReception, ['centre' => $this->getUser()->getCtCentreId()]);
        $form_reception = $this->createForm(CtReceptionReceptionDisableType::class, $ctReception, ["disabled" => true]);
        $form_reception->handleRequest($request);

        if ($form_reception->isSubmitted() && $form_reception->isValid()) {
            $ctVehicule->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctVehicule->setCtMarqueId($ctReception->getCtVehiculeId()->getCtMarqueId());
            $ctVehicule->setVhcCylindre($ctReception->getCtVehiculeId()->getVhcCylindre());
            $ctVehicule->setVhcPuissance($ctReception->getCtVehiculeId()->getVhcPuissance());
            $ctVehicule->setVhcPoidsVide($ctReception->getCtVehiculeId()->getVhcPoidsVide());
            $ctVehicule->setVhcChargeUtile($ctReception->getCtVehiculeId()->getVhcChargeUtile());
            $ctVehicule->setVhcNumSerie($ctReception->getCtVehiculeId()->getVhcNumSerie());
            $ctVehicule->setVhcNumMoteur($ctReception->getCtVehiculeId()->getVhcNumMoteur());
            $ctVehicule->setVhcLongueur($ctReception->getCtVehiculeId()->getVhcLongueur());
            $ctVehicule->setVhcLargeur($ctReception->getCtVehiculeId()->getVhcLargeur());
            $ctVehicule->setVhcHauteur($ctReception->getCtVehiculeId()->getVhcHauteur());
            $ctVehicule->setVhcCreated(new \DateTime());
            $ctVehicule->setVhcType($ctReception->getCtVehiculeId()->getVhcType());
            $ctVehicule->setVhcPoidsTotalCharge($ctReception->getCtVehiculeId()->getVhcPoidsVide() + $ctReception->getCtVehiculeId()->getVhcChargeUtile());

            $ctVehiculeRepository->add($ctVehicule, true);

            $ctReception_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctReception_new->setCtMotifId($ctReception->getCtMotifId());
            $ctReception_new->setCtTypeReceptionId($ctReception->getCtTypeReceptionId());
            $ctReception_new->setCtUserId($this->getUser());
            $ctReception_new->setCtVerificateurId($ctReception->getCtVerificateurId());
            $ctReception_new->setCtUtilisationId($ctReception->getCtUtilisationId());
            $ctReception_new->setCtVehiculeId($ctVehicule);
            $ctReception_new->setRcpMiseService($ctReception->getRcpMiseService());
            $ctReception_new->setRcpImmatriculation($ctReception->getRcpImmatriculation());
            $ctReception_new->setRcpProprietaire($ctReception->getRcpProprietaire());
            $ctReception_new->setRcpProfession($ctReception->getRcpProfession());
            $ctReception_new->setRcpAdresse($ctReception->getRcpAdresse());
            $ctReception_new->setRcpNbrAssis($ctReception->getRcpNbrAssis());
            $ctReception_new->setRcpNgrDebout($ctReception->getRcpNgrDebout());
            $ctReception_new->setCtSourceEnergieId($ctReception->getCtSourceEnergieId());
            $ctReception_new->setCtCarrosserieId($ctReception->getCtCarrosserieId());
            $date = new \DateTime();
            //$date = $date->format('Y-m-d');
            //$ctReception_new->setRcpNumGroup($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctReception->getCtTypeReceptionId()->getTprcpLibelle().'/'.$date->format("Y"));
            $ctReception_new->setRcpNumGroup($ctReception->getRcpNumGroup());
            $ctReception_new->setRcpCreated(new \DateTime());
            $ctReception_new->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctReception_new->setRcpIsActive(true);
            $ctReception_new->setRcpGenere(intval($ctReception->getRcpGenere()));
            $ctReception_new->setRcpObservation($ctReception->getRcpObservation()." ");

            //$ctReceptionRepository->add($ctReception_new, true);
            $ctReception_new->setRcpNumPv($ctReception_new->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.'RECEP/'.$date->format("Y"));
            //$ctReceptionRepository->add($ctReception_new, true);

            /* if($ctReception->getId() != null && $ctReception->getId() < $ctReception_new->getId()){
                $ctReception->setRcpIsActive(false);

                $ctReceptionRepository->add($ctReception, true);
            } */

            $message = "Réception modifier avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            return $this->redirectToRoute('app_ct_app_reception_reception_modification', ["id" => $ctReception->getId()]);
        }
        return $this->render('ct_app_reception/modification_reception.html.twig', [
            'form_reception' => $form_reception->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            'boutton' => 'Modifier',
        ]);
    }

    /**
     * @Route("/reception_modification/{id}", name="app_ct_app_reception_reception_modification", methods={"GET", "POST"})
     */
    public function ReceptionModification(Request $request, int $id, CtTypeReceptionRepository $ctTypeReceptionRepository, CtRoleRepository $ctRoleRepository, CtReceptionRepository $ctReceptionRepository, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $ctReception = $ctReceptionRepository->findOneBy(["id" => $id]);
        $ctVehicule = new CtVehicule();
        $ctReception_new = new CtReception();
        $message = "";
        $enregistrement_ok = False;
        

        $form_reception = $this->createForm(CtReceptionReceptionType::class, $ctReception, ['centre' => $this->getUser()->getCtCentreId()]);
        //$form_reception = $this->createForm(CtReceptionReceptionDisableType::class, $ctReception, ["disabled" => false]);
        $form_reception->handleRequest($request);

        if ($form_reception->isSubmitted() && $form_reception->isValid()) {
            $ctVehicule->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctVehicule->setCtMarqueId($ctReception->getCtVehiculeId()->getCtMarqueId());
            $ctVehicule->setVhcCylindre($ctReception->getCtVehiculeId()->getVhcCylindre());
            $ctVehicule->setVhcPuissance($ctReception->getCtVehiculeId()->getVhcPuissance());
            $ctVehicule->setVhcPoidsVide($ctReception->getCtVehiculeId()->getVhcPoidsVide());
            $ctVehicule->setVhcChargeUtile($ctReception->getCtVehiculeId()->getVhcChargeUtile());
            $ctVehicule->setVhcNumSerie($ctReception->getCtVehiculeId()->getVhcNumSerie());
            $ctVehicule->setVhcNumMoteur($ctReception->getCtVehiculeId()->getVhcNumMoteur());
            $ctVehicule->setVhcLongueur($ctReception->getCtVehiculeId()->getVhcLongueur());
            $ctVehicule->setVhcLargeur($ctReception->getCtVehiculeId()->getVhcLargeur());
            $ctVehicule->setVhcHauteur($ctReception->getCtVehiculeId()->getVhcHauteur());
            //$ctVehicule->setVhcCreated(new \DateTime());
            $ctVehicule->setVhcCreated($ctReception->getCtVehiculeId()->getVhcCreated());
            $ctVehicule->setVhcType($ctReception->getCtVehiculeId()->getVhcType());
            $ctVehicule->setVhcPoidsTotalCharge($ctReception->getCtVehiculeId()->getVhcPoidsVide() + $ctReception->getCtVehiculeId()->getVhcChargeUtile());

            $ctVehiculeRepository->add($ctVehicule, true);

            $ctReception_new->setCtCentreId($this->getUser()->getCtCentreId());
            $ctReception_new->setCtMotifId($ctReception->getCtMotifId());
            $ctReception_new->setCtTypeReceptionId($ctReception->getCtTypeReceptionId());
            $ctReception_new->setCtUserId($this->getUser());
            $ctReception_new->setCtVerificateurId($ctReception->getCtVerificateurId());
            $ctReception_new->setCtUtilisationId($ctReception->getCtUtilisationId());
            $ctReception_new->setCtVehiculeId($ctVehicule);
            $ctReception_new->setRcpMiseService($ctReception->getRcpMiseService());
            $ctReception_new->setRcpImmatriculation($ctReception->getRcpImmatriculation());
            $ctReception_new->setRcpProprietaire($ctReception->getRcpProprietaire());
            $ctReception_new->setRcpProfession($ctReception->getRcpProfession());
            $ctReception_new->setRcpAdresse($ctReception->getRcpAdresse());
            $ctReception_new->setRcpNbrAssis($ctReception->getRcpNbrAssis());
            $ctReception_new->setRcpNgrDebout($ctReception->getRcpNgrDebout());
            $ctReception_new->setCtSourceEnergieId($ctReception->getCtSourceEnergieId());
            $ctReception_new->setCtCarrosserieId($ctReception->getCtCarrosserieId());
            $date = new \DateTime();
            //$date = $date->format('Y-m-d');
            //$ctReception_new->setRcpNumGroup($date->format('d').'/'.$date->format('m').'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.$ctReception->getCtTypeReceptionId()->getTprcpLibelle().'/'.$date->format("Y"));
            $ctReception_new->setRcpNumGroup($ctReception->getRcpNumGroup());
            $ctReception_new->setRcpCreated($ctReception->getRcpCreated());
            $ctReception_new->setCtGenreId($ctReception->getCtVehiculeId()->getCtGenreId());
            $ctReception_new->setRcpIsActive(true);
            $ctReception_new->setRcpGenere(intval($ctReception->getRcpGenere()));
            $ctReception_new->setRcpObservation($ctReception->getRcpObservation()." modification de la reception, ID initial : ".$ctReception->getId().", date modification ".$date->format("d/m/Y"));

            $ctReceptionRepository->add($ctReception_new, true);
            $ctReception_new->setRcpNumPv($ctReception_new->getId().'/'.'CENSERO/'.$this->getUser()->getCtCentreId()->getCtProvinceId()->getPrvCode().'/'.$this->getUser()->getCtCentreId()->getCtrCode().'/'.'RECEP/'.$date->format("Y"));
            $ctReceptionRepository->add($ctReception_new, true);

            /* if($ctReception->getId() != null && $ctReception->getId() < $ctReception_new->getId()){
                $ctReception->setRcpIsActive(false);

                $ctReceptionRepository->add($ctReception, true);
            } */

            $message = "Réception modifier avec succes";
            $enregistrement_ok = true;

            // assiana redirection mandeha amin'ny générer rehefa vita ilay izy
            return $this->redirectToRoute('app_ct_app_reception_recapitulation_reception_isole', ["id" => $ctReception_new->getId(), "old" => $ctReception->getId()]);
        }
        return $this->render('ct_app_reception/modification_reception.html.twig', [
            'form_reception' => $form_reception->createView(),
            'message' => $message,
            'enregistrement_ok' => $enregistrement_ok,
            'boutton' => 'Enregistrer',
        ]);
    }

    /**
     * @Route("/recapitulation_reception_isole/{id}", name="app_ct_app_reception_recapitulation_reception_isole", methods={"GET", "POST"})
     */
    public function RecapitulationReceptionIsole(Request $request, int $id, CtVehiculeRepository $ctVehiculeRepository, CtReceptionRepository $ctReceptionRepository): Response
    {
        //récapitulation réception isolé
        //$id = $request->query->get("id");
        $identification = intval($id);
        $reception = $ctReceptionRepository->findOneBy(["id" => $identification, "rcp_is_active" => true], ["id" => "DESC"]);
        $vehicule = $ctVehiculeRepository->findOneBy(["id" => $reception->getCtVehiculeId()], ["id" => "DESC"]);
        /* if($vehicule == null){
            return $this->redirectToRoute('app_ct_app_reception_creer_reception_isole');
        } */
        $reception_data = ["id" => $identification,
            "ct_genre_id" => $vehicule->getCtGenreId()->getGrLibelle(),
            "ct_marque_id" => $vehicule->getCtMarqueId()->getMrqLibelle(),
            "vhc_type" => $vehicule->getVhcType(),
            "vhc_num_serie" => $vehicule->getVhcNumSerie(),
            "vhc_num_moteur" => $vehicule->getVhcNumMoteur(),
            "ct_carrosserie_id" => $reception->getCtCarrosserieId()->getCrsLibelle(),
            "ct_source_energie_id" => $reception->getCtSourceEnergieId()->getSreLibelle(),
            "vhc_cylindre" => $vehicule->getVhcCylindre(),
            "vhc_puissance" => $vehicule->getVhcPuissance(),
            "vhc_poids_vide" => $vehicule->getVhcPoidsVide(),
            "vhc_charge_utile" => $vehicule->getVhcChargeUtile(),
            "vhc_poids_total_charge" => $vehicule->getVhcPoidsTotalCharge(),
            "vhc_longueur" => $vehicule->getVhcLongueur(),
            "vhc_largeur" => $vehicule->getVhcLargeur(),
            "vhc_hauteur" => $vehicule->getVhcHauteur(),
            "ct_utilisation_id" => $reception->getCtUtilisationId()->getUtLibelle(),
            "ct_motif_id" => $reception->getCtMotifId()->getMtfLibelle(),
            "rcp_immatriculation" => $reception->getRcpImmatriculation(),
            "rcp_proprietaire" => $reception->getRcpProprietaire(),
            "rcp_profession" => $reception->getRcpProfession(),
            "rcp_adresse" => $reception->getRcpAdresse(),
            "rcp_nbr_assis" => $reception->getRcpNbrAssis(),
            "rcp_ngr_debout" => $reception->getRcpNgrDebout(),
            "rcp_mise_service" => $reception->getRcpMiseService(),
            "ct_verificateur_id" => $reception->getCtVerificateurId()->getUsrNom(),
        ];
        return $this->render('ct_app_reception/recapitulation_reception_isole.html.twig', [
            'reception' => $reception_data,
        ]);
    }

    /**
     * @Route("/recapitulation_reception_par_type/{id}", name="app_ct_app_reception_recapitulation_reception_par_type", methods={"GET", "POST"})
     */
    public function RecapitulationReceptionParType(Request $request, string $id, CtVehiculeRepository $ctVehiculeRepository, CtReceptionRepository $ctReceptionRepository): Response
    {
        //récapitulation réception isolé
        //$id = $request->query->get("id");
        $liste_reception = new ArrayCollection();
        $identification = $id;
        $receptions = $ctReceptionRepository->findBy(["rcp_num_group" => $identification, "rcp_is_active" => true], ["id" => "DESC"]);
        foreach($receptions as $reception){
            $vehicule = $ctVehiculeRepository->findOneBy(["id" => $reception->getCtVehiculeId()], ["id" => "DESC"]);
            /* if($vehicule == null){
                return $this->redirectToRoute('app_ct_app_reception_creer_reception_isole');
            } */
            $reception_data = ["id" => $identification,
                "ct_genre_id" => $vehicule->getCtGenreId()->getGrLibelle(),
                "ct_marque_id" => $vehicule->getCtMarqueId()->getMrqLibelle(),
                "vhc_type" => $vehicule->getVhcType(),
                "vhc_num_serie" => $vehicule->getVhcNumSerie(),
                "vhc_num_moteur" => $vehicule->getVhcNumMoteur(),
                "ct_carrosserie_id" => $reception->getCtCarrosserieId()->getCrsLibelle(),
                "ct_source_energie_id" => $reception->getCtSourceEnergieId()->getSreLibelle(),
                "vhc_cylindre" => $vehicule->getVhcCylindre(),
                "vhc_puissance" => $vehicule->getVhcPuissance(),
                "vhc_poids_vide" => $vehicule->getVhcPoidsVide(),
                "vhc_charge_utile" => $vehicule->getVhcChargeUtile(),
                "vhc_poids_total_charge" => $vehicule->getVhcPoidsTotalCharge(),
                "vhc_longueur" => $vehicule->getVhcLongueur(),
                "vhc_largeur" => $vehicule->getVhcLargeur(),
                "vhc_hauteur" => $vehicule->getVhcHauteur(),
                "ct_utilisation_id" => $reception->getCtUtilisationId()->getUtLibelle(),
                "ct_motif_id" => $reception->getCtMotifId()->getMtfLibelle(),
                "rcp_immatriculation" => $reception->getRcpImmatriculation(),
                "rcp_proprietaire" => $reception->getRcpProprietaire(),
                "rcp_profession" => $reception->getRcpProfession(),
                "rcp_adresse" => $reception->getRcpAdresse(),
                "rcp_nbr_assis" => $reception->getRcpNbrAssis(),
                "rcp_ngr_debout" => $reception->getRcpNgrDebout(),
                "rcp_mise_service" => $reception->getRcpMiseService(),
                "ct_verificateur_id" => $reception->getCtVerificateurId()->getUsrNom(),
            ];
            $liste_reception->add($reception_data);
        }

        return $this->render('ct_app_reception/recapitulation_reception_par_type.html.twig', [
            'receptions' => $liste_reception,
            'id' => $identification,
        ]);
        //récapitulation réception isolé
        /* return $this->render('ct_app_reception/index.html.twig', [
            'controller_name' => 'CtAppReceptionController',
        ]); */
    }

    /**
     * @Route("/recapitulation_reception_isole/{id}/{old}", name="app_ct_app_reception_recapitulation_reception_isole", methods={"GET", "POST"})
     */
    public function RecapitulationReceptionModification(Request $request, int $id, int $old, CtVehiculeRepository $ctVehiculeRepository, CtReceptionRepository $ctReceptionRepository): Response
    {
        //récapitulation réception isolé
        //$id = $request->query->get("id");
        $identification = intval($id);
        $reception = $ctReceptionRepository->findOneBy(["id" => $identification, "rcp_is_active" => true], ["id" => "DESC"]);
        $vehicule = $ctVehiculeRepository->findOneBy(["id" => $reception->getCtVehiculeId()], ["id" => "DESC"]);
        $reception_old = $ctReceptionRepository->findOneBy(["id" => $old], ["id" => "DESC"]);
        $reception_old->setRcpIsActive(false);
        $ctReceptionRepository->add($reception_old, true);
        /* if($vehicule == null){
            return $this->redirectToRoute('app_ct_app_reception_creer_reception_isole');
        } */
        $reception_data = ["id" => $identification,
            "ct_genre_id" => $vehicule->getCtGenreId()->getGrLibelle(),
            "ct_marque_id" => $vehicule->getCtMarqueId()->getMrqLibelle(),
            "vhc_type" => $vehicule->getVhcType(),
            "vhc_num_serie" => $vehicule->getVhcNumSerie(),
            "vhc_num_moteur" => $vehicule->getVhcNumMoteur(),
            "ct_carrosserie_id" => $reception->getCtCarrosserieId()->getCrsLibelle(),
            "ct_source_energie_id" => $reception->getCtSourceEnergieId()->getSreLibelle(),
            "vhc_cylindre" => $vehicule->getVhcCylindre(),
            "vhc_puissance" => $vehicule->getVhcPuissance(),
            "vhc_poids_vide" => $vehicule->getVhcPoidsVide(),
            "vhc_charge_utile" => $vehicule->getVhcChargeUtile(),
            "vhc_poids_total_charge" => $vehicule->getVhcPoidsTotalCharge(),
            "vhc_longueur" => $vehicule->getVhcLongueur(),
            "vhc_largeur" => $vehicule->getVhcLargeur(),
            "vhc_hauteur" => $vehicule->getVhcHauteur(),
            "ct_utilisation_id" => $reception->getCtUtilisationId()->getUtLibelle(),
            "ct_motif_id" => $reception->getCtMotifId()->getMtfLibelle(),
            "rcp_immatriculation" => $reception->getRcpImmatriculation(),
            "rcp_proprietaire" => $reception->getRcpProprietaire(),
            "rcp_profession" => $reception->getRcpProfession(),
            "rcp_adresse" => $reception->getRcpAdresse(),
            "rcp_nbr_assis" => $reception->getRcpNbrAssis(),
            "rcp_ngr_debout" => $reception->getRcpNgrDebout(),
            "rcp_mise_service" => $reception->getRcpMiseService(),
            "ct_verificateur_id" => $reception->getCtVerificateurId()->getUsrNom(),
        ];
        return $this->render('ct_app_reception/recapitulation_reception_isole.html.twig', [
            'reception' => $reception_data,
        ]);
    }
}
