<?php

namespace App\Controller;

use App\Entity\CtCentre;
use App\Form\CtCentreType;
use App\Repository\CtCentreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_centre")
 */
class CtCentreController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_centre_index", methods={"GET"})
     */
    public function index(CtCentreRepository $ctCentreRepository): Response
    {
        return $this->render('ct_centre/index.html.twig', [
            'ct_centres' => $ctCentreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_centre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtCentreRepository $ctCentreRepository): Response
    {
        $ctCentre = new CtCentre();
        $form = $this->createForm(CtCentreType::class, $ctCentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctCentreRepository->add($ctCentre, true);

            return $this->redirectToRoute('app_ct_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_centre/new.html.twig', [
            'ct_centre' => $ctCentre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_centre_show", methods={"GET"})
     */
    public function show(CtCentre $ctCentre): Response
    {
        return $this->render('ct_centre/show.html.twig', [
            'ct_centre' => $ctCentre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_centre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtCentre $ctCentre, CtCentreRepository $ctCentreRepository): Response
    {
        $form = $this->createForm(CtCentreType::class, $ctCentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctCentreRepository->add($ctCentre, true);

            return $this->redirectToRoute('app_ct_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_centre/edit.html.twig', [
            'ct_centre' => $ctCentre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_centre_delete", methods={"POST"})
     */
    public function delete(Request $request, CtCentre $ctCentre, CtCentreRepository $ctCentreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctCentre->getId(), $request->request->get('_token'))) {
            $ctCentreRepository->remove($ctCentre, true);
        }

        return $this->redirectToRoute('app_ct_centre_index', [], Response::HTTP_SEE_OTHER);
    }
}
