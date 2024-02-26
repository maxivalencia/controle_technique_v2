<?php

namespace App\Controller;

use App\Entity\CtConstAvDedCarac;
use App\Form\CtConstAvDedCaracType;
use App\Repository\CtConstAvDedCaracRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_const_av_ded_carac")
 */
class CtConstAvDedCaracController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_const_av_ded_carac_index", methods={"GET"})
     */
    public function index(CtConstAvDedCaracRepository $ctConstAvDedCaracRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctConstAvDedCaracRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_const_av_ded_carac/index.html.twig', [
            'ct_const_av_ded_caracs' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_const_av_ded_carac_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtConstAvDedCaracRepository $ctConstAvDedCaracRepository): Response
    {
        $ctConstAvDedCarac = new CtConstAvDedCarac();
        $form = $this->createForm(CtConstAvDedCaracType::class, $ctConstAvDedCarac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedCaracRepository->add($ctConstAvDedCarac, true);

            return $this->redirectToRoute('app_ct_const_av_ded_carac_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_const_av_ded_carac/new.html.twig', [
            'ct_const_av_ded_carac' => $ctConstAvDedCarac,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_const_av_ded_carac_show", methods={"GET"})
     */
    public function show(CtConstAvDedCarac $ctConstAvDedCarac): Response
    {
        return $this->render('ct_const_av_ded_carac/show.html.twig', [
            'ct_const_av_ded_carac' => $ctConstAvDedCarac,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_const_av_ded_carac_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtConstAvDedCarac $ctConstAvDedCarac, CtConstAvDedCaracRepository $ctConstAvDedCaracRepository): Response
    {
        $form = $this->createForm(CtConstAvDedCaracType::class, $ctConstAvDedCarac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedCaracRepository->add($ctConstAvDedCarac, true);

            return $this->redirectToRoute('app_ct_const_av_ded_carac_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_const_av_ded_carac/edit.html.twig', [
            'ct_const_av_ded_carac' => $ctConstAvDedCarac,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_const_av_ded_carac_delete", methods={"POST"})
     */
    public function delete(Request $request, CtConstAvDedCarac $ctConstAvDedCarac, CtConstAvDedCaracRepository $ctConstAvDedCaracRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctConstAvDedCarac->getId(), $request->request->get('_token'))) {
            $ctConstAvDedCaracRepository->remove($ctConstAvDedCarac, true);
        }

        return $this->redirectToRoute('app_ct_const_av_ded_carac_index', [], Response::HTTP_SEE_OTHER);
    }
}
