<?php

namespace App\Controller;

use App\Entity\CtDroitPTAC;
use App\Form\CtDroitPTACType;
use App\Repository\CtDroitPTACRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_droit_ptac")
 */
class CtDroitPTACController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_droit_p_t_a_c_index", methods={"GET"})
     */
    public function index(CtDroitPTACRepository $ctDroitPTACRepository): Response
    {
        return $this->render('ct_droit_ptac/index.html.twig', [
            'ct_droit_p_t_a_cs' => $ctDroitPTACRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_droit_p_t_a_c_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtDroitPTACRepository $ctDroitPTACRepository): Response
    {
        $ctDroitPTAC = new CtDroitPTAC();
        $form = $this->createForm(CtDroitPTACType::class, $ctDroitPTAC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctDroitPTACRepository->add($ctDroitPTAC, true);

            return $this->redirectToRoute('app_ct_droit_p_t_a_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_droit_ptac/new.html.twig', [
            'ct_droit_p_t_a_c' => $ctDroitPTAC,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_droit_p_t_a_c_show", methods={"GET"})
     */
    public function show(CtDroitPTAC $ctDroitPTAC): Response
    {
        return $this->render('ct_droit_ptac/show.html.twig', [
            'ct_droit_p_t_a_c' => $ctDroitPTAC,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_droit_p_t_a_c_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtDroitPTAC $ctDroitPTAC, CtDroitPTACRepository $ctDroitPTACRepository): Response
    {
        $form = $this->createForm(CtDroitPTACType::class, $ctDroitPTAC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctDroitPTACRepository->add($ctDroitPTAC, true);

            return $this->redirectToRoute('app_ct_droit_p_t_a_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_droit_ptac/edit.html.twig', [
            'ct_droit_p_t_a_c' => $ctDroitPTAC,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_droit_p_t_a_c_delete", methods={"POST"})
     */
    public function delete(Request $request, CtDroitPTAC $ctDroitPTAC, CtDroitPTACRepository $ctDroitPTACRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctDroitPTAC->getId(), $request->request->get('_token'))) {
            $ctDroitPTACRepository->remove($ctDroitPTAC, true);
        }

        return $this->redirectToRoute('app_ct_droit_p_t_a_c_index', [], Response::HTTP_SEE_OTHER);
    }
}
