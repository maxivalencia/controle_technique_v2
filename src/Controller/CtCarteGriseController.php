<?php

namespace App\Controller;

use App\Entity\CtCarteGrise;
use App\Form\CtCarteGriseType;
use App\Repository\CtCarteGriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_carte_grise")
 */
class CtCarteGriseController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_carte_grise_index", methods={"GET"})
     */
    public function index(CtCarteGriseRepository $ctCarteGriseRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctCarteGriseRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_carte_grise/index.html.twig', [
            'ct_carte_grises' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_carte_grise_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $ctCarteGrise = new CtCarteGrise();
        $form = $this->createForm(CtCarteGriseType::class, $ctCarteGrise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctCarteGriseRepository->add($ctCarteGrise, true);

            return $this->redirectToRoute('app_ct_carte_grise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_carte_grise/new.html.twig', [
            'ct_carte_grise' => $ctCarteGrise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_carte_grise_show", methods={"GET"})
     */
    public function show(CtCarteGrise $ctCarteGrise): Response
    {
        return $this->render('ct_carte_grise/show.html.twig', [
            'ct_carte_grise' => $ctCarteGrise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_carte_grise_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtCarteGrise $ctCarteGrise, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        $form = $this->createForm(CtCarteGriseType::class, $ctCarteGrise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctCarteGriseRepository->add($ctCarteGrise, true);

            return $this->redirectToRoute('app_ct_carte_grise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_carte_grise/edit.html.twig', [
            'ct_carte_grise' => $ctCarteGrise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_carte_grise_delete", methods={"POST"})
     */
    public function delete(Request $request, CtCarteGrise $ctCarteGrise, CtCarteGriseRepository $ctCarteGriseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctCarteGrise->getId(), $request->request->get('_token'))) {
            $ctCarteGriseRepository->remove($ctCarteGrise, true);
        }

        return $this->redirectToRoute('app_ct_carte_grise_index', [], Response::HTTP_SEE_OTHER);
    }
}
