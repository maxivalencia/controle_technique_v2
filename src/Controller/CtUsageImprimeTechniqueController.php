<?php

namespace App\Controller;

use App\Entity\CtUsageImprimeTechnique;
use App\Form\CtUsageImprimeTechniqueType;
use App\Repository\CtUsageImprimeTechniqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_usage_imprime_technique")
 */
class CtUsageImprimeTechniqueController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_usage_imprime_technique_index", methods={"GET"})
     */
    public function index(CtUsageImprimeTechniqueRepository $ctUsageImprimeTechniqueRepository): Response
    {
        return $this->render('ct_usage_imprime_technique/index.html.twig', [
            'ct_usage_imprime_techniques' => $ctUsageImprimeTechniqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_usage_imprime_technique_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechniqueRepository): Response
    {
        $ctUsageImprimeTechnique = new CtUsageImprimeTechnique();
        $form = $this->createForm(CtUsageImprimeTechniqueType::class, $ctUsageImprimeTechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUsageImprimeTechniqueRepository->add($ctUsageImprimeTechnique, true);

            return $this->redirectToRoute('app_ct_usage_imprime_technique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_usage_imprime_technique/new.html.twig', [
            'ct_usage_imprime_technique' => $ctUsageImprimeTechnique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_usage_imprime_technique_show", methods={"GET"})
     */
    public function show(CtUsageImprimeTechnique $ctUsageImprimeTechnique): Response
    {
        return $this->render('ct_usage_imprime_technique/show.html.twig', [
            'ct_usage_imprime_technique' => $ctUsageImprimeTechnique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_usage_imprime_technique_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtUsageImprimeTechnique $ctUsageImprimeTechnique, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechniqueRepository): Response
    {
        $form = $this->createForm(CtUsageImprimeTechniqueType::class, $ctUsageImprimeTechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUsageImprimeTechniqueRepository->add($ctUsageImprimeTechnique, true);

            return $this->redirectToRoute('app_ct_usage_imprime_technique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_usage_imprime_technique/edit.html.twig', [
            'ct_usage_imprime_technique' => $ctUsageImprimeTechnique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_usage_imprime_technique_delete", methods={"POST"})
     */
    public function delete(Request $request, CtUsageImprimeTechnique $ctUsageImprimeTechnique, CtUsageImprimeTechniqueRepository $ctUsageImprimeTechniqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctUsageImprimeTechnique->getId(), $request->request->get('_token'))) {
            $ctUsageImprimeTechniqueRepository->remove($ctUsageImprimeTechnique, true);
        }

        return $this->redirectToRoute('app_ct_usage_imprime_technique_index', [], Response::HTTP_SEE_OTHER);
    }
}
