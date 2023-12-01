<?php

namespace App\Controller;

use App\Entity\CtTypeVisite;
use App\Form\CtTypeVisiteType;
use App\Repository\CtTypeVisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_type_visite")
 */
class CtTypeVisiteController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_type_visite_index", methods={"GET"})
     */
    public function index(CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        return $this->render('ct_type_visite/index.html.twig', [
            'ct_type_visites' => $ctTypeVisiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_type_visite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        $ctTypeVisite = new CtTypeVisite();
        $form = $this->createForm(CtTypeVisiteType::class, $ctTypeVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeVisiteRepository->add($ctTypeVisite, true);

            return $this->redirectToRoute('app_ct_type_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_type_visite/new.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_type_visite_show", methods={"GET"})
     */
    public function show(CtTypeVisite $ctTypeVisite): Response
    {
        return $this->render('ct_type_visite/show.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_type_visite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtTypeVisite $ctTypeVisite, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        $form = $this->createForm(CtTypeVisiteType::class, $ctTypeVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeVisiteRepository->add($ctTypeVisite, true);

            return $this->redirectToRoute('app_ct_type_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_type_visite/edit.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_type_visite_delete", methods={"POST"})
     */
    public function delete(Request $request, CtTypeVisite $ctTypeVisite, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctTypeVisite->getId(), $request->request->get('_token'))) {
            $ctTypeVisiteRepository->remove($ctTypeVisite, true);
        }

        return $this->redirectToRoute('app_ct_type_visite_index', [], Response::HTTP_SEE_OTHER);
    }
}
