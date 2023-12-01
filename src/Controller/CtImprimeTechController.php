<?php

namespace App\Controller;

use App\Entity\CtImprimeTech;
use App\Form\CtImprimeTechType;
use App\Repository\CtImprimeTechRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_imprime_tech")
 */
class CtImprimeTechController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_imprime_tech_index", methods={"GET"})
     */
    public function index(CtImprimeTechRepository $ctImprimeTechRepository): Response
    {
        return $this->render('ct_imprime_tech/index.html.twig', [
            'ct_imprime_teches' => $ctImprimeTechRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_imprime_tech_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtImprimeTechRepository $ctImprimeTechRepository): Response
    {
        $ctImprimeTech = new CtImprimeTech();
        $form = $this->createForm(CtImprimeTechType::class, $ctImprimeTech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctImprimeTechRepository->add($ctImprimeTech, true);

            return $this->redirectToRoute('app_ct_imprime_tech_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_imprime_tech/new.html.twig', [
            'ct_imprime_tech' => $ctImprimeTech,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_imprime_tech_show", methods={"GET"})
     */
    public function show(CtImprimeTech $ctImprimeTech): Response
    {
        return $this->render('ct_imprime_tech/show.html.twig', [
            'ct_imprime_tech' => $ctImprimeTech,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_imprime_tech_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtImprimeTech $ctImprimeTech, CtImprimeTechRepository $ctImprimeTechRepository): Response
    {
        $form = $this->createForm(CtImprimeTechType::class, $ctImprimeTech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctImprimeTechRepository->add($ctImprimeTech, true);

            return $this->redirectToRoute('app_ct_imprime_tech_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_imprime_tech/edit.html.twig', [
            'ct_imprime_tech' => $ctImprimeTech,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_imprime_tech_delete", methods={"POST"})
     */
    public function delete(Request $request, CtImprimeTech $ctImprimeTech, CtImprimeTechRepository $ctImprimeTechRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctImprimeTech->getId(), $request->request->get('_token'))) {
            $ctImprimeTechRepository->remove($ctImprimeTech, true);
        }

        return $this->redirectToRoute('app_ct_imprime_tech_index', [], Response::HTTP_SEE_OTHER);
    }
}
