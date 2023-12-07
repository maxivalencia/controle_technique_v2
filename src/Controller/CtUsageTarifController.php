<?php

namespace App\Controller;

use App\Entity\CtUsageTarif;
use App\Form\CtUsageTarifType;
use App\Repository\CtUsageTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_usage_tarif")
 */
class CtUsageTarifController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_usage_tarif_index", methods={"GET"})
     */
    public function index(CtUsageTarifRepository $ctUsageTarifRepository): Response
    {
        return $this->render('ct_usage_tarif/index.html.twig', [
            'ct_usage_tarifs' => $ctUsageTarifRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_usage_tarif_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtUsageTarifRepository $ctUsageTarifRepository): Response
    {
        $ctUsageTarif = new CtUsageTarif();
        $form = $this->createForm(CtUsageTarifType::class, $ctUsageTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUsageTarifRepository->add($ctUsageTarif, true);

            return $this->redirectToRoute('app_ct_usage_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_usage_tarif/new.html.twig', [
            'ct_usage_tarif' => $ctUsageTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_usage_tarif_show", methods={"GET"})
     */
    public function show(CtUsageTarif $ctUsageTarif): Response
    {
        return $this->render('ct_usage_tarif/show.html.twig', [
            'ct_usage_tarif' => $ctUsageTarif,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_usage_tarif_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtUsageTarif $ctUsageTarif, CtUsageTarifRepository $ctUsageTarifRepository): Response
    {
        $form = $this->createForm(CtUsageTarifType::class, $ctUsageTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUsageTarifRepository->add($ctUsageTarif, true);

            return $this->redirectToRoute('app_ct_usage_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_usage_tarif/edit.html.twig', [
            'ct_usage_tarif' => $ctUsageTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_usage_tarif_delete", methods={"POST"})
     */
    public function delete(Request $request, CtUsageTarif $ctUsageTarif, CtUsageTarifRepository $ctUsageTarifRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctUsageTarif->getId(), $request->request->get('_token'))) {
            $ctUsageTarifRepository->remove($ctUsageTarif, true);
        }

        return $this->redirectToRoute('app_ct_usage_tarif_index', [], Response::HTTP_SEE_OTHER);
    }
}
