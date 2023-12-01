<?php

namespace App\Controller;

use App\Entity\CtMotif;
use App\Form\CtMotifType;
use App\Repository\CtMotifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_motif")
 */
class CtMotifController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_motif_index", methods={"GET"})
     */
    public function index(CtMotifRepository $ctMotifRepository): Response
    {
        return $this->render('ct_motif/index.html.twig', [
            'ct_motifs' => $ctMotifRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_motif_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtMotifRepository $ctMotifRepository): Response
    {
        $ctMotif = new CtMotif();
        $form = $this->createForm(CtMotifType::class, $ctMotif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctMotifRepository->add($ctMotif, true);

            return $this->redirectToRoute('app_ct_motif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_motif/new.html.twig', [
            'ct_motif' => $ctMotif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_motif_show", methods={"GET"})
     */
    public function show(CtMotif $ctMotif): Response
    {
        return $this->render('ct_motif/show.html.twig', [
            'ct_motif' => $ctMotif,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_motif_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtMotif $ctMotif, CtMotifRepository $ctMotifRepository): Response
    {
        $form = $this->createForm(CtMotifType::class, $ctMotif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctMotifRepository->add($ctMotif, true);

            return $this->redirectToRoute('app_ct_motif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_motif/edit.html.twig', [
            'ct_motif' => $ctMotif,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_motif_delete", methods={"POST"})
     */
    public function delete(Request $request, CtMotif $ctMotif, CtMotifRepository $ctMotifRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctMotif->getId(), $request->request->get('_token'))) {
            $ctMotifRepository->remove($ctMotif, true);
        }

        return $this->redirectToRoute('app_ct_motif_index', [], Response::HTTP_SEE_OTHER);
    }
}
