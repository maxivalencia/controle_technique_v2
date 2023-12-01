<?php

namespace App\Controller;

use App\Entity\CtArretePrix;
use App\Form\CtArretePrixType;
use App\Repository\CtArretePrixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_arrete_prix")
 */
class CtArretePrixController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_arrete_prix_index", methods={"GET"})
     */
    public function index(CtArretePrixRepository $ctArretePrixRepository): Response
    {
        return $this->render('ct_arrete_prix/index.html.twig', [
            'ct_arrete_prixes' => $ctArretePrixRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_arrete_prix_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtArretePrixRepository $ctArretePrixRepository): Response
    {
        $ctArretePrix = new CtArretePrix();
        $form = $this->createForm(CtArretePrixType::class, $ctArretePrix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctArretePrixRepository->add($ctArretePrix, true);

            return $this->redirectToRoute('app_ct_arrete_prix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_arrete_prix/new.html.twig', [
            'ct_arrete_prix' => $ctArretePrix,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_arrete_prix_show", methods={"GET"})
     */
    public function show(CtArretePrix $ctArretePrix): Response
    {
        return $this->render('ct_arrete_prix/show.html.twig', [
            'ct_arrete_prix' => $ctArretePrix,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_arrete_prix_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtArretePrix $ctArretePrix, CtArretePrixRepository $ctArretePrixRepository): Response
    {
        $form = $this->createForm(CtArretePrixType::class, $ctArretePrix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctArretePrixRepository->add($ctArretePrix, true);

            return $this->redirectToRoute('app_ct_arrete_prix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_arrete_prix/edit.html.twig', [
            'ct_arrete_prix' => $ctArretePrix,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_arrete_prix_delete", methods={"POST"})
     */
    public function delete(Request $request, CtArretePrix $ctArretePrix, CtArretePrixRepository $ctArretePrixRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctArretePrix->getId(), $request->request->get('_token'))) {
            $ctArretePrixRepository->remove($ctArretePrix, true);
        }

        return $this->redirectToRoute('app_ct_arrete_prix_index', [], Response::HTTP_SEE_OTHER);
    }
}
