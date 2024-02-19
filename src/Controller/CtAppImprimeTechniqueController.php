<?php

namespace App\Controller;

use App\Entity\CtImprimeTech;
use App\Form\CtImprimeTechType;
use App\Repository\CtImprimeTechRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function ActiverImprimer(): Response
    {
        return $this->render('ct_app_imprime_technique/activer_imprimer.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/liste_utiliser", name="app_ct_app_imprime_technique_liste_utiliser", methods={"GET", "POST"})
     */
    public function ListeUtiliser(): Response
    {
        return $this->render('ct_app_imprime_technique/liste_utiliser.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/liste_non_utiliser", name="app_ct_app_imprime_technique_liste_non_utiliser", methods={"GET", "POST"})
     */
    public function ListeNonUtiliser(): Response
    {
        return $this->render('ct_app_imprime_technique/liste_non_utiliser.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/mise_a_jour_utilisation", name="app_ct_app_imprime_technique_mise_a_jour_utilisation", methods={"GET", "POST"})
     */
    public function MiseAJourUtilisation(): Response
    {
        return $this->render('ct_app_imprime_technique/mise_a_jour_utilisation.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/mise_a_jour_multiple", name="app_ct_app_imprime_technique_mise_a_jour_multiple", methods={"GET", "POST"})
     */
    public function MiseAJourMultiple(): Response
    {
        return $this->render('ct_app_imprime_technique/mise_a_jour_multiple.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/liste_imprimer", name="app_ct_app_imprime_technique_liste_imprimer", methods={"GET", "POST"})
     */
    public function ListeImprimer(CtImprimeTechRepository $ctImprimeTechRepository): Response
    {
        return $this->render('ct_app_imprime_technique/liste_imprimer.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
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
    public function RechercherBordereau(): Response
    {
        return $this->render('ct_app_imprime_technique/recherche_bordereau.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/liste_bordereau", name="app_ct_app_imprime_technique_liste_bordereau", methods={"GET", "POST"})
     */
    public function ListeBordereau(): Response
    {
        return $this->render('ct_app_imprime_technique/liste_bordereau.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }

    /**
     * @Route("/creer_bordereau", name="app_ct_app_imprime_technique_creer_bordereau", methods={"GET", "POST"})
     */
    public function CreerBordereau(): Response
    {
        return $this->render('ct_app_imprime_technique/creer_bordereau.html.twig', [
            'controller_name' => 'CtAppImprimeTechniqueController',
        ]);
    }
}
