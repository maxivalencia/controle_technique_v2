<?php

namespace App\Controller;

use App\Entity\CtGenreTarif;
use App\Form\CtGenreTarifType;
use App\Repository\CtGenreTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_genre_tarif")
 */
class CtGenreTarifController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_genre_tarif_index", methods={"GET"})
     */
    public function index(CtGenreTarifRepository $ctGenreTarifRepository): Response
    {
        return $this->render('ct_genre_tarif/index.html.twig', [
            'ct_genre_tarifs' => $ctGenreTarifRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_genre_tarif_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtGenreTarifRepository $ctGenreTarifRepository): Response
    {
        $ctGenreTarif = new CtGenreTarif();
        $form = $this->createForm(CtGenreTarifType::class, $ctGenreTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctGenreTarifRepository->add($ctGenreTarif, true);

            return $this->redirectToRoute('app_ct_genre_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_genre_tarif/new.html.twig', [
            'ct_genre_tarif' => $ctGenreTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_genre_tarif_show", methods={"GET"})
     */
    public function show(CtGenreTarif $ctGenreTarif): Response
    {
        return $this->render('ct_genre_tarif/show.html.twig', [
            'ct_genre_tarif' => $ctGenreTarif,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_genre_tarif_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtGenreTarif $ctGenreTarif, CtGenreTarifRepository $ctGenreTarifRepository): Response
    {
        $form = $this->createForm(CtGenreTarifType::class, $ctGenreTarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctGenreTarifRepository->add($ctGenreTarif, true);

            return $this->redirectToRoute('app_ct_genre_tarif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_genre_tarif/edit.html.twig', [
            'ct_genre_tarif' => $ctGenreTarif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_genre_tarif_delete", methods={"POST"})
     */
    public function delete(Request $request, CtGenreTarif $ctGenreTarif, CtGenreTarifRepository $ctGenreTarifRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctGenreTarif->getId(), $request->request->get('_token'))) {
            $ctGenreTarifRepository->remove($ctGenreTarif, true);
        }

        return $this->redirectToRoute('app_ct_genre_tarif_index', [], Response::HTTP_SEE_OTHER);
    }
}
