<?php

namespace App\Controller;

use App\Entity\CtConstAvDedType;
use App\Form\CtConstAvDedTypeType;
use App\Repository\CtConstAvDedTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
