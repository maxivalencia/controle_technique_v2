<?php

namespace App\Controller;

use App\Entity\CtSourceEnergie;
use App\Form\CtSourceEnergieType;
use App\Repository\CtSourceEnergieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_source_energie")
 */
class CtSourceEnergieController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_source_energie_index", methods={"GET"})
     */
    public function index(CtSourceEnergieRepository $ctSourceEnergieRepository): Response
    {
        return $this->render('ct_source_energie/index.html.twig', [
            'ct_source_energies' => $ctSourceEnergieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_source_energie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtSourceEnergieRepository $ctSourceEnergieRepository): Response
    {
        $ctSourceEnergie = new CtSourceEnergie();
        $form = $this->createForm(CtSourceEnergieType::class, $ctSourceEnergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctSourceEnergieRepository->add($ctSourceEnergie, true);

            return $this->redirectToRoute('app_ct_source_energie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_source_energie/new.html.twig', [
            'ct_source_energie' => $ctSourceEnergie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_source_energie_show", methods={"GET"})
     */
    public function show(CtSourceEnergie $ctSourceEnergie): Response
    {
        return $this->render('ct_source_energie/show.html.twig', [
            'ct_source_energie' => $ctSourceEnergie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_source_energie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtSourceEnergie $ctSourceEnergie, CtSourceEnergieRepository $ctSourceEnergieRepository): Response
    {
        $form = $this->createForm(CtSourceEnergieType::class, $ctSourceEnergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctSourceEnergieRepository->add($ctSourceEnergie, true);

            return $this->redirectToRoute('app_ct_source_energie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_source_energie/edit.html.twig', [
            'ct_source_energie' => $ctSourceEnergie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_source_energie_delete", methods={"POST"})
     */
    public function delete(Request $request, CtSourceEnergie $ctSourceEnergie, CtSourceEnergieRepository $ctSourceEnergieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctSourceEnergie->getId(), $request->request->get('_token'))) {
            $ctSourceEnergieRepository->remove($ctSourceEnergie, true);
        }

        return $this->redirectToRoute('app_ct_source_energie_index', [], Response::HTTP_SEE_OTHER);
    }
}
