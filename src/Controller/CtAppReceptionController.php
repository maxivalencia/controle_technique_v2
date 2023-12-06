<?php

namespace App\Controller;

use App\Entity\CtTypeReception;
use App\Form\CtTypeReceptionType;
use App\Repository\CtTypeReceptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function delete(Request $request, CtTypeReception $ctTypeReception, CtTypeReceptionRepository $ctTypeReceptionRepository): Response
    {
        $ctTypeReceptionRepository->remove($ctTypeReception, true);

        return $this->redirectToRoute('app_ct_app_reception_liste_type', [], Response::HTTP_SEE_OTHER);
    }
}
