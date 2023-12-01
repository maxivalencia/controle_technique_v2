<?php

namespace App\Controller;

use App\Entity\CtMotifTarif;
use App\Form\CtMotifTarifType;
use App\Repository\CtMotifTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_motif_tarif")
 */
class CtMotifTarifController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_motif_tarif_index", methods={"GET"})
     */
    public function index(CtMotifTarifRepository $ctMotifTarifRepository): Response
    {
        return $this->render('ct_motif_tarif/index.html.twig', [
            'ct_motif_tarifs' => $ctMotifTarifRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_motif_tarif_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtMotifTarifRepository $ctMotifTarifRepository): Response
    {
        $ctMotifTarif = new CtMotifTarif();
        $form = $this->createForm(CtMotifTarifType::class, $ctMotifTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctMotifTarifRepository->add($ctMotifTarif, true);

            return $this->redirectToRoute('app_ct_motif_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_motif_tarif/new.html.twig', [
            'ct_motif_tarif' => $ctMotifTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_motif_tarif_show", methods={"GET"})
     */
    public function show(CtMotifTarif $ctMotifTarif): Response
    {
        return $this->render('ct_motif_tarif/show.html.twig', [
            'ct_motif_tarif' => $ctMotifTarif,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_motif_tarif_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtMotifTarif $ctMotifTarif, CtMotifTarifRepository $ctMotifTarifRepository): Response
    {
        $form = $this->createForm(CtMotifTarifType::class, $ctMotifTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctMotifTarifRepository->add($ctMotifTarif, true);

            return $this->redirectToRoute('app_ct_motif_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_motif_tarif/edit.html.twig', [
            'ct_motif_tarif' => $ctMotifTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_motif_tarif_delete", methods={"POST"})
     */
    public function delete(Request $request, CtMotifTarif $ctMotifTarif, CtMotifTarifRepository $ctMotifTarifRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctMotifTarif->getId(), $request->request->get('_token'))) {
            $ctMotifTarifRepository->remove($ctMotifTarif, true);
        }

        return $this->redirectToRoute('app_ct_motif_tarif_index', [], Response::HTTP_SEE_OTHER);
    }
}
