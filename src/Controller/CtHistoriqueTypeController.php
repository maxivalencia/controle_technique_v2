<?php

namespace App\Controller;

use App\Entity\CtHistoriqueType;
use App\Form\CtHistoriqueTypeType;
use App\Repository\CtHistoriqueTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_historique_type")
 */
class CtHistoriqueTypeController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_historique_type_index", methods={"GET"})
     */
    public function index(CtHistoriqueTypeRepository $ctHistoriqueTypeRepository): Response
    {
        return $this->render('ct_historique_type/index.html.twig', [
            'ct_historique_types' => $ctHistoriqueTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_historique_type_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtHistoriqueTypeRepository $ctHistoriqueTypeRepository): Response
    {
        $ctHistoriqueType = new CtHistoriqueType();
        $form = $this->createForm(CtHistoriqueTypeType::class, $ctHistoriqueType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctHistoriqueTypeRepository->add($ctHistoriqueType, true);

            return $this->redirectToRoute('app_ct_historique_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_historique_type/new.html.twig', [
            'ct_historique_type' => $ctHistoriqueType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_historique_type_show", methods={"GET"})
     */
    public function show(CtHistoriqueType $ctHistoriqueType): Response
    {
        return $this->render('ct_historique_type/show.html.twig', [
            'ct_historique_type' => $ctHistoriqueType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_historique_type_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtHistoriqueType $ctHistoriqueType, CtHistoriqueTypeRepository $ctHistoriqueTypeRepository): Response
    {
        $form = $this->createForm(CtHistoriqueTypeType::class, $ctHistoriqueType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctHistoriqueTypeRepository->add($ctHistoriqueType, true);

            return $this->redirectToRoute('app_ct_historique_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_historique_type/edit.html.twig', [
            'ct_historique_type' => $ctHistoriqueType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_historique_type_delete", methods={"POST"})
     */
    public function delete(Request $request, CtHistoriqueType $ctHistoriqueType, CtHistoriqueTypeRepository $ctHistoriqueTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctHistoriqueType->getId(), $request->request->get('_token'))) {
            $ctHistoriqueTypeRepository->remove($ctHistoriqueType, true);
        }

        return $this->redirectToRoute('app_ct_historique_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
