<?php

namespace App\Controller;

use App\Entity\CtZoneDesserte;
use App\Form\CtZoneDesserteType;
use App\Repository\CtZoneDesserteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_zone_desserte")
 */
class CtZoneDesserteController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_zone_desserte_index", methods={"GET"})
     */
    public function index(CtZoneDesserteRepository $ctZoneDesserteRepository): Response
    {
        return $this->render('ct_zone_desserte/index.html.twig', [
            'ct_zone_dessertes' => $ctZoneDesserteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_zone_desserte_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtZoneDesserteRepository $ctZoneDesserteRepository): Response
    {
        $ctZoneDesserte = new CtZoneDesserte();
        $form = $this->createForm(CtZoneDesserteType::class, $ctZoneDesserte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctZoneDesserteRepository->add($ctZoneDesserte, true);

            return $this->redirectToRoute('app_ct_zone_desserte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_zone_desserte/new.html.twig', [
            'ct_zone_desserte' => $ctZoneDesserte,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_zone_desserte_show", methods={"GET"})
     */
    public function show(CtZoneDesserte $ctZoneDesserte): Response
    {
        return $this->render('ct_zone_desserte/show.html.twig', [
            'ct_zone_desserte' => $ctZoneDesserte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_zone_desserte_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtZoneDesserte $ctZoneDesserte, CtZoneDesserteRepository $ctZoneDesserteRepository): Response
    {
        $form = $this->createForm(CtZoneDesserteType::class, $ctZoneDesserte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctZoneDesserteRepository->add($ctZoneDesserte, true);

            return $this->redirectToRoute('app_ct_zone_desserte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_zone_desserte/edit.html.twig', [
            'ct_zone_desserte' => $ctZoneDesserte,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_zone_desserte_delete", methods={"POST"})
     */
    public function delete(Request $request, CtZoneDesserte $ctZoneDesserte, CtZoneDesserteRepository $ctZoneDesserteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctZoneDesserte->getId(), $request->request->get('_token'))) {
            $ctZoneDesserteRepository->remove($ctZoneDesserte, true);
        }

        return $this->redirectToRoute('app_ct_zone_desserte_index', [], Response::HTTP_SEE_OTHER);
    }
}
