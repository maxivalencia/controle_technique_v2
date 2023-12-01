<?php

namespace App\Controller;

use App\Entity\CtCarrosserie;
use App\Form\CtCarrosserieType;
use App\Repository\CtCarrosserieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_carrosserie")
 */
class CtCarrosserieController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_carrosserie_index", methods={"GET"})
     */
    public function index(CtCarrosserieRepository $ctCarrosserieRepository): Response
    {
        return $this->render('ct_carrosserie/index.html.twig', [
            'ct_carrosseries' => $ctCarrosserieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_carrosserie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtCarrosserieRepository $ctCarrosserieRepository): Response
    {
        $ctCarrosserie = new CtCarrosserie();
        $form = $this->createForm(CtCarrosserieType::class, $ctCarrosserie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctCarrosserieRepository->add($ctCarrosserie, true);

            return $this->redirectToRoute('app_ct_carrosserie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_carrosserie/new.html.twig', [
            'ct_carrosserie' => $ctCarrosserie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_carrosserie_show", methods={"GET"})
     */
    public function show(CtCarrosserie $ctCarrosserie): Response
    {
        return $this->render('ct_carrosserie/show.html.twig', [
            'ct_carrosserie' => $ctCarrosserie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_carrosserie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtCarrosserie $ctCarrosserie, CtCarrosserieRepository $ctCarrosserieRepository): Response
    {
        $form = $this->createForm(CtCarrosserieType::class, $ctCarrosserie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctCarrosserieRepository->add($ctCarrosserie, true);

            return $this->redirectToRoute('app_ct_carrosserie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_carrosserie/edit.html.twig', [
            'ct_carrosserie' => $ctCarrosserie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_carrosserie_delete", methods={"POST"})
     */
    public function delete(Request $request, CtCarrosserie $ctCarrosserie, CtCarrosserieRepository $ctCarrosserieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctCarrosserie->getId(), $request->request->get('_token'))) {
            $ctCarrosserieRepository->remove($ctCarrosserie, true);
        }

        return $this->redirectToRoute('app_ct_carrosserie_index', [], Response::HTTP_SEE_OTHER);
    }
}
