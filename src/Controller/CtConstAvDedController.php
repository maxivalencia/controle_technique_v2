<?php

namespace App\Controller;

use App\Entity\CtConstAvDed;
use App\Form\CtConstAvDedType;
use App\Repository\CtConstAvDedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_const_av_ded")
 */
class CtConstAvDedController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_const_av_ded_index", methods={"GET"})
     */
    public function index(CtConstAvDedRepository $ctConstAvDedRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctConstAvDedRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_const_av_ded/index.html.twig', [
            'ct_const_av_deds' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_const_av_ded_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtConstAvDedRepository $ctConstAvDedRepository): Response
    {
        $ctConstAvDed = new CtConstAvDed();
        $form = $this->createForm(CtConstAvDedType::class, $ctConstAvDed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedRepository->add($ctConstAvDed, true);

            return $this->redirectToRoute('app_ct_const_av_ded_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_const_av_ded/new.html.twig', [
            'ct_const_av_ded' => $ctConstAvDed,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_const_av_ded_show", methods={"GET"})
     */
    public function show(CtConstAvDed $ctConstAvDed): Response
    {
        return $this->render('ct_const_av_ded/show.html.twig', [
            'ct_const_av_ded' => $ctConstAvDed,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_const_av_ded_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtConstAvDed $ctConstAvDed, CtConstAvDedRepository $ctConstAvDedRepository): Response
    {
        $form = $this->createForm(CtConstAvDedType::class, $ctConstAvDed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctConstAvDedRepository->add($ctConstAvDed, true);

            return $this->redirectToRoute('app_ct_const_av_ded_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_const_av_ded/edit.html.twig', [
            'ct_const_av_ded' => $ctConstAvDed,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_const_av_ded_delete", methods={"POST"})
     */
    public function delete(Request $request, CtConstAvDed $ctConstAvDed, CtConstAvDedRepository $ctConstAvDedRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctConstAvDed->getId(), $request->request->get('_token'))) {
            $ctConstAvDedRepository->remove($ctConstAvDed, true);
        }

        return $this->redirectToRoute('app_ct_const_av_ded_index', [], Response::HTTP_SEE_OTHER);
    }
}
