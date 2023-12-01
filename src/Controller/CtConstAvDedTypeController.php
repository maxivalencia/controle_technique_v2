<?php

namespace App\Controller;

use App\Entity\CtConstAvDedType;
use App\Form\CtConstAvDedTypeType;
use App\Repository\CtConstAvDedTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_const_av_ded_type")
 */
class CtConstAvDedTypeController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_const_av_ded_type_index", methods={"GET"})
     */
    public function index(CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        return $this->render('ct_const_av_ded_type/index.html.twig', [
            'ct_const_av_ded_types' => $ctConstAvDedTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_const_av_ded_type_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        $ctConstAvDedType = new CtConstAvDedType();
        $form = $this->createForm(CtConstAvDedTypeType::class, $ctConstAvDedType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedTypeRepository->add($ctConstAvDedType, true);

            return $this->redirectToRoute('app_ct_const_av_ded_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_const_av_ded_type/new.html.twig', [
            'ct_const_av_ded_type' => $ctConstAvDedType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_const_av_ded_type_show", methods={"GET"})
     */
    public function show(CtConstAvDedType $ctConstAvDedType): Response
    {
        return $this->render('ct_const_av_ded_type/show.html.twig', [
            'ct_const_av_ded_type' => $ctConstAvDedType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_const_av_ded_type_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtConstAvDedType $ctConstAvDedType, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        $form = $this->createForm(CtConstAvDedTypeType::class, $ctConstAvDedType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedTypeRepository->add($ctConstAvDedType, true);

            return $this->redirectToRoute('app_ct_const_av_ded_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_const_av_ded_type/edit.html.twig', [
            'ct_const_av_ded_type' => $ctConstAvDedType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_const_av_ded_type_delete", methods={"POST"})
     */
    public function delete(Request $request, CtConstAvDedType $ctConstAvDedType, CtConstAvDedTypeRepository $ctConstAvDedTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctConstAvDedType->getId(), $request->request->get('_token'))) {
            $ctConstAvDedTypeRepository->remove($ctConstAvDedType, true);
        }

        return $this->redirectToRoute('app_ct_const_av_ded_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
