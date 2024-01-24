<?php

namespace App\Controller;

use App\Entity\CtAutreDonne;
use App\Form\CtAutreDonneType;
use App\Repository\CtAutreDonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ct/autre/donne')]
class CtAutreDonneController extends AbstractController
{
    #[Route('/', name: 'app_ct_autre_donne_index', methods: ['GET'])]
    public function index(CtAutreDonneRepository $ctAutreDonneRepository): Response
    {
        return $this->render('ct_autre_donne/index.html.twig', [
            'ct_autre_donnes' => $ctAutreDonneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ct_autre_donne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ctAutreDonne = new CtAutreDonne();
        $form = $this->createForm(CtAutreDonneType::class, $ctAutreDonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ctAutreDonne);
            $entityManager->flush();

            return $this->redirectToRoute('app_ct_autre_donne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_autre_donne/new.html.twig', [
            'ct_autre_donne' => $ctAutreDonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ct_autre_donne_show', methods: ['GET'])]
    public function show(CtAutreDonne $ctAutreDonne): Response
    {
        return $this->render('ct_autre_donne/show.html.twig', [
            'ct_autre_donne' => $ctAutreDonne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ct_autre_donne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CtAutreDonne $ctAutreDonne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CtAutreDonneType::class, $ctAutreDonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ct_autre_donne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_autre_donne/edit.html.twig', [
            'ct_autre_donne' => $ctAutreDonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ct_autre_donne_delete', methods: ['POST'])]
    public function delete(Request $request, CtAutreDonne $ctAutreDonne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctAutreDonne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ctAutreDonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ct_autre_donne_index', [], Response::HTTP_SEE_OTHER);
    }
}
