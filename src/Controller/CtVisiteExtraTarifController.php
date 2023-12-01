<?php

namespace App\Controller;

use App\Entity\CtVisiteExtraTarif;
use App\Form\CtVisiteExtraTarifType;
use App\Repository\CtVisiteExtraTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_visite_extra_tarif")
 */
class CtVisiteExtraTarifController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_visite_extra_tarif_index", methods={"GET"})
     */
    public function index(CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository): Response
    {
        return $this->render('ct_visite_extra_tarif/index.html.twig', [
            'ct_visite_extra_tarifs' => $ctVisiteExtraTarifRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_visite_extra_tarif_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository): Response
    {
        $ctVisiteExtraTarif = new CtVisiteExtraTarif();
        $form = $this->createForm(CtVisiteExtraTarifType::class, $ctVisiteExtraTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctVisiteExtraTarifRepository->add($ctVisiteExtraTarif, true);

            return $this->redirectToRoute('app_ct_visite_extra_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_visite_extra_tarif/new.html.twig', [
            'ct_visite_extra_tarif' => $ctVisiteExtraTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_visite_extra_tarif_show", methods={"GET"})
     */
    public function show(CtVisiteExtraTarif $ctVisiteExtraTarif): Response
    {
        return $this->render('ct_visite_extra_tarif/show.html.twig', [
            'ct_visite_extra_tarif' => $ctVisiteExtraTarif,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_visite_extra_tarif_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtVisiteExtraTarif $ctVisiteExtraTarif, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository): Response
    {
        $form = $this->createForm(CtVisiteExtraTarifType::class, $ctVisiteExtraTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctVisiteExtraTarifRepository->add($ctVisiteExtraTarif, true);

            return $this->redirectToRoute('app_ct_visite_extra_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_visite_extra_tarif/edit.html.twig', [
            'ct_visite_extra_tarif' => $ctVisiteExtraTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_visite_extra_tarif_delete", methods={"POST"})
     */
    public function delete(Request $request, CtVisiteExtraTarif $ctVisiteExtraTarif, CtVisiteExtraTarifRepository $ctVisiteExtraTarifRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctVisiteExtraTarif->getId(), $request->request->get('_token'))) {
            $ctVisiteExtraTarifRepository->remove($ctVisiteExtraTarif, true);
        }

        return $this->redirectToRoute('app_ct_visite_extra_tarif_index', [], Response::HTTP_SEE_OTHER);
    }
}
