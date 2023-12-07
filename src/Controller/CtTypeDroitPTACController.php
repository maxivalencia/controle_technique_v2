<?php

namespace App\Controller;

use App\Entity\CtTypeDroitPTAC;
use App\Form\CtTypeDroitPTACType;
use App\Repository\CtTypeDroitPTACRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_type_droit_ptac")
 */
class CtTypeDroitPTACController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_type_droit_p_t_a_c_index", methods={"GET"})
     */
    public function index(CtTypeDroitPTACRepository $ctTypeDroitPTACRepository): Response
    {
        return $this->render('ct_type_droit_ptac/index.html.twig', [
            'ct_type_droit_p_t_a_cs' => $ctTypeDroitPTACRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_type_droit_p_t_a_c_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository): Response
    {
        $ctTypeDroitPTAC = new CtTypeDroitPTAC();
        $form = $this->createForm(CtTypeDroitPTACType::class, $ctTypeDroitPTAC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeDroitPTACRepository->add($ctTypeDroitPTAC, true);

            return $this->redirectToRoute('app_ct_type_droit_p_t_a_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_type_droit_ptac/new.html.twig', [
            'ct_type_droit_p_t_a_c' => $ctTypeDroitPTAC,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_type_droit_p_t_a_c_show", methods={"GET"})
     */
    public function show(CtTypeDroitPTAC $ctTypeDroitPTAC): Response
    {
        return $this->render('ct_type_droit_ptac/show.html.twig', [
            'ct_type_droit_p_t_a_c' => $ctTypeDroitPTAC,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_type_droit_p_t_a_c_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtTypeDroitPTAC $ctTypeDroitPTAC, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository): Response
    {
        $form = $this->createForm(CtTypeDroitPTACType::class, $ctTypeDroitPTAC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeDroitPTACRepository->add($ctTypeDroitPTAC, true);

            return $this->redirectToRoute('app_ct_type_droit_p_t_a_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_type_droit_ptac/edit.html.twig', [
            'ct_type_droit_p_t_a_c' => $ctTypeDroitPTAC,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_type_droit_p_t_a_c_delete", methods={"POST"})
     */
    public function delete(Request $request, CtTypeDroitPTAC $ctTypeDroitPTAC, CtTypeDroitPTACRepository $ctTypeDroitPTACRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctTypeDroitPTAC->getId(), $request->request->get('_token'))) {
            $ctTypeDroitPTACRepository->remove($ctTypeDroitPTAC, true);
        }

        return $this->redirectToRoute('app_ct_type_droit_p_t_a_c_index', [], Response::HTTP_SEE_OTHER);
    }
}
