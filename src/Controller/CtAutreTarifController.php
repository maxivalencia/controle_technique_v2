<?php

namespace App\Controller;

use App\Entity\CtAutreTarif;
use App\Form\CtAutreTarifType;
use App\Repository\CtAutreTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_autre_tarif")
 */
class CtAutreTarifController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_autre_tarif_index", methods={"GET"})
     */
    public function index(CtAutreTarifRepository $ctAutreTarifRepository): Response
    {
        return $this->render('ct_autre_tarif/index.html.twig', [
            'ct_autre_tarifs' => $ctAutreTarifRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_autre_tarif_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtAutreTarifRepository $ctAutreTarifRepository): Response
    {
        $ctAutreTarif = new CtAutreTarif();
        $form = $this->createForm(CtAutreTarifType::class, $ctAutreTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctAutreTarifRepository->add($ctAutreTarif, true);

            return $this->redirectToRoute('app_ct_autre_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_autre_tarif/new.html.twig', [
            'ct_autre_tarif' => $ctAutreTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_autre_tarif_show", methods={"GET"})
     */
    public function show(CtAutreTarif $ctAutreTarif): Response
    {
        return $this->render('ct_autre_tarif/show.html.twig', [
            'ct_autre_tarif' => $ctAutreTarif,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_autre_tarif_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtAutreTarif $ctAutreTarif, CtAutreTarifRepository $ctAutreTarifRepository): Response
    {
        $form = $this->createForm(CtAutreTarifType::class, $ctAutreTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctAutreTarifRepository->add($ctAutreTarif, true);

            return $this->redirectToRoute('app_ct_autre_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_autre_tarif/edit.html.twig', [
            'ct_autre_tarif' => $ctAutreTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_autre_tarif_delete", methods={"POST"})
     */
    public function delete(Request $request, CtAutreTarif $ctAutreTarif, CtAutreTarifRepository $ctAutreTarifRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctAutreTarif->getId(), $request->request->get('_token'))) {
            $ctAutreTarifRepository->remove($ctAutreTarif, true);
        }

        return $this->redirectToRoute('app_ct_autre_tarif_index', [], Response::HTTP_SEE_OTHER);
    }
}
