<?php

namespace App\Controller;

use App\Entity\CtUtilisation;
use App\Form\CtUtilisationType;
use App\Repository\CtUtilisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_utilisation")
 */
class CtUtilisationController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_utilisation_index", methods={"GET"})
     */
    public function index(CtUtilisationRepository $ctUtilisationRepository): Response
    {
        return $this->render('ct_utilisation/index.html.twig', [
            'ct_utilisations' => $ctUtilisationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_utilisation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtUtilisationRepository $ctUtilisationRepository): Response
    {
        $ctUtilisation = new CtUtilisation();
        $form = $this->createForm(CtUtilisationType::class, $ctUtilisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUtilisationRepository->add($ctUtilisation, true);

            return $this->redirectToRoute('app_ct_utilisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_utilisation/new.html.twig', [
            'ct_utilisation' => $ctUtilisation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_utilisation_show", methods={"GET"})
     */
    public function show(CtUtilisation $ctUtilisation): Response
    {
        return $this->render('ct_utilisation/show.html.twig', [
            'ct_utilisation' => $ctUtilisation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_utilisation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtUtilisation $ctUtilisation, CtUtilisationRepository $ctUtilisationRepository): Response
    {
        $form = $this->createForm(CtUtilisationType::class, $ctUtilisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUtilisationRepository->add($ctUtilisation, true);

            return $this->redirectToRoute('app_ct_utilisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_utilisation/edit.html.twig', [
            'ct_utilisation' => $ctUtilisation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_utilisation_delete", methods={"POST"})
     */
    public function delete(Request $request, CtUtilisation $ctUtilisation, CtUtilisationRepository $ctUtilisationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctUtilisation->getId(), $request->request->get('_token'))) {
            $ctUtilisationRepository->remove($ctUtilisation, true);
        }

        return $this->redirectToRoute('app_ct_utilisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
