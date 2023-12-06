<?php

namespace App\Controller;

use App\Entity\CtUsage;
use App\Form\CtUsageType;
use App\Repository\CtUsageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_usage")
 */
class CtUsageController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_usage_index", methods={"GET"})
     */
    public function index(CtUsageRepository $ctUsageRepository): Response
    {
        return $this->render('ct_usage/index.html.twig', [
            'ct_usages' => $ctUsageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_usage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtUsageRepository $ctUsageRepository): Response
    {
        $ctUsage = new CtUsage();
        $form = $this->createForm(CtUsageType::class, $ctUsage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUsageRepository->add($ctUsage, true);

            return $this->redirectToRoute('app_ct_usage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_usage/new.html.twig', [
            'ct_usage' => $ctUsage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_usage_show", methods={"GET"})
     */
    public function show(CtUsage $ctUsage): Response
    {
        return $this->render('ct_usage/show.html.twig', [
            'ct_usage' => $ctUsage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_usage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtUsage $ctUsage, CtUsageRepository $ctUsageRepository): Response
    {
        $form = $this->createForm(CtUsageType::class, $ctUsage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUsageRepository->add($ctUsage, true);

            return $this->redirectToRoute('app_ct_usage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_usage/edit.html.twig', [
            'ct_usage' => $ctUsage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_usage_delete", methods={"POST"})
     */
    public function delete(Request $request, CtUsage $ctUsage, CtUsageRepository $ctUsageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctUsage->getId(), $request->request->get('_token'))) {
            $ctUsageRepository->remove($ctUsage, true);
        }

        return $this->redirectToRoute('app_ct_usage_index', [], Response::HTTP_SEE_OTHER);
    }
}
