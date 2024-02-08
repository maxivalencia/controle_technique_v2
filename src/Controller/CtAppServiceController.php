<?php

namespace App\Controller;

use App\Entity\CtVisiteExtra;
use App\Form\CtVisiteExtraType;
use App\Repository\CtVisiteExtraRepository;
use App\Entity\CtAutreVente;
use App\Form\CtAutreVenteType;
use App\Form\CtAutreVenteAutreVenteType;
use App\Repository\CtAutreVenteRepository;
use App\Entity\CtAutreTarif;
use App\Form\CtAutreTarifType;
use App\Repository\CtAutreTarifRepository;
use App\Entity\CtCentre;
use App\Entity\CtUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * @Route("/ct_app_service")
 */
class CtAppServiceController extends AbstractController
{
    //#[Route('/ct/app/service', name: 'app_ct_app_service')]
    /**
     * @Route("/", name="app_ct_app_service")
     */
    public function index(): Response
    {
        return $this->render('ct_app_service/index.html.twig', [
            'controller_name' => 'CtAppServiceController',
        ]);
    }

    /**
     * @Route("/liste_service", name="app_ct_app_liste_service", methods={"GET", "POST"})
     */
    public function ListeService(CtAutreVenteRepository $ctAutreVenteRepository, CtVisiteExtraRepository $ctVisiteExtraRepository): Response
    {
        // mbola mila amboarina ito satria mila asiana date du jour ilay filtre eto
        $listes = $ctAutreVenteRepository->findBy(["ct_centre_id" => $this->getUser()->getCtCentreId()]);
        return $this->render('ct_app_service/liste_autre_service.html.twig', [
            'controller_name' => 'CtAppServiceController',
            'ct_autre_services' => $listes,
            'total' => count($listes),
        ]);
    }

    /**
     * @Route("/new_service", name="app_ct_app_new_service", methods={"GET", "POST"})
     */
    public function NewService(Request $request, CtAutreVenteRepository $ctAutreVenteRepository): Response
    {
        $ctAutreVente = new CtAutreVente();
        $form_autre_vente = $this->createForm(CtAutreVenteAutreVenteType::class, $ctAutreVente, ["centre" => $this->getUser()->getCtCentreId()]);
        $form_autre_vente->handleRequest($request);

        if ($form_autre_vente->isSubmitted() && $form_autre_vente->isValid()) {
            $ctAutreVenteRepository->add($ctAutreVente, true);

            return $this->redirectToRoute('app_ct_app_liste_service', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_service/new_autre_service.html.twig', [
            'ct_autre_vente' => $ctAutreVente,
            'form_autre_vente' => $form_autre_vente,
        ]);
    }


    /**
     * @Route("/creer_service", name="app_ct_app_creer_service", methods={"GET", "POST"})
     */
    public function CreerService(Request $request): Response
    {
        $form_bilan = $this->createFormBuilder()
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
        $form_feuille_de_caisse->handleRequest($request);
        return $this->render('ct_app_service/creer_autre_service.html.twig', [
            'form_bilan' => $form_bilan->createView(),
            'form_feuille_de_caisse' => $form_feuille_de_caisse->createView(),
        ]);
    }
}
