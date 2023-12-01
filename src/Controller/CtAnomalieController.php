<?php

namespace App\Controller;

use App\Entity\CtAnomalie;
use App\Form\CtAnomalieType;
use App\Repository\CtAnomalieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_anomalie")
 */
class CtAnomalieController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_anomalie_index", methods={"GET"})
     */
    public function index(Request $request,CtAnomalieRepository $ctAnomalieRepository): Response
    {
        return $this->render('ct_anomalie/index.html.twig', [
            'ct_anomalies' => $ctAnomalieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_anomalie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtAnomalieRepository $ctAnomalieRepository): Response
    {
        $ctAnomalie = new CtAnomalie();
        $form = $this->createForm(CtAnomalieType::class, $ctAnomalie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctAnomalieRepository->add($ctAnomalie, true);

            return $this->redirectToRoute('app_ct_anomalie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_anomalie/new.html.twig', [
            'ct_anomalie' => $ctAnomalie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_anomalie_show", methods={"GET"})
     */
    public function show(CtAnomalie $ctAnomalie): Response
    {
        return $this->render('ct_anomalie/show.html.twig', [
            'ct_anomalie' => $ctAnomalie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_anomalie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtAnomalie $ctAnomalie, CtAnomalieRepository $ctAnomalieRepository): Response
    {
        $form = $this->createForm(CtAnomalieType::class, $ctAnomalie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctAnomalieRepository->add($ctAnomalie, true);

            return $this->redirectToRoute('app_ct_anomalie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_anomalie/edit.html.twig', [
            'ct_anomalie' => $ctAnomalie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_anomalie_delete", methods={"POST"})
     */
    public function delete(Request $request, CtAnomalie $ctAnomalie, CtAnomalieRepository $ctAnomalieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctAnomalie->getId(), $request->request->get('_token'))) {
            $ctAnomalieRepository->remove($ctAnomalie, true);
        }

        return $this->redirectToRoute('app_ct_anomalie_index', [], Response::HTTP_SEE_OTHER);
    }
}
