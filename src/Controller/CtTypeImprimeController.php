<?php

namespace App\Controller;

use App\Entity\CtTypeImprime;
use App\Form\CtTypeImprimeType;
use App\Repository\CtTypeImprimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('/ct_type_imprime')]
/**
* @Route("/ct_type_imprime")
*/
class CtTypeImprimeController extends AbstractController
{
    //#[Route('/', name: 'app_ct_type_imprime_index', methods: ['GET'])]
    /**
     * @Route("/", name="app_ct_type_imprime_index", methods={"GET"})
     */
    public function index(CtTypeImprimeRepository $ctTypeImprimeRepository): Response
    {
        return $this->render('ct_type_imprime/index.html.twig', [
            'ct_type_imprimes' => $ctTypeImprimeRepository->findAll(),
        ]);
    }

    //#[Route('/new', name: 'app_ct_type_imprime_new', methods: ['GET', 'POST'])]
    /**
     * @Route("/new", name="app_ct_type_imprime_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ctTypeImprime = new CtTypeImprime();
        $form = $this->createForm(CtTypeImprimeType::class, $ctTypeImprime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ctTypeImprime);
            $entityManager->flush();

            return $this->redirectToRoute('app_ct_type_imprime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_type_imprime/new.html.twig', [
            'ct_type_imprime' => $ctTypeImprime,
            'form' => $form,
        ]);
    }

    //#[Route('/{id}', name: 'app_ct_type_imprime_show', methods: ['GET'])]
    /**
     * @Route("/{id}", name="app_ct_type_imprime_show", methods={"GET"})
     */
    public function show(CtTypeImprime $ctTypeImprime): Response
    {
        return $this->render('ct_type_imprime/show.html.twig', [
            'ct_type_imprime' => $ctTypeImprime,
        ]);
    }

    //#[Route('/{id}/edit', name: 'app_ct_type_imprime_edit', methods: ['GET', 'POST'])]
    /**
     * @Route("/{id}/edit", name="app_ct_type_imprime_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtTypeImprime $ctTypeImprime, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CtTypeImprimeType::class, $ctTypeImprime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ct_type_imprime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_type_imprime/edit.html.twig', [
            'ct_type_imprime' => $ctTypeImprime,
            'form' => $form,
        ]);
    }

    //#[Route('/{id}', name: 'app_ct_type_imprime_delete', methods: ['POST'])]
    /**
     * @Route("/{id}", name="app_ct_type_imprime_delete", methods={"POST"})
     */
    public function delete(Request $request, CtTypeImprime $ctTypeImprime, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctTypeImprime->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ctTypeImprime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ct_type_imprime_index', [], Response::HTTP_SEE_OTHER);
    }
}
