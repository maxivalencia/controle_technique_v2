<?php

namespace App\Controller;

use App\Entity\CtHistorique;
use App\Form\CtHistoriqueType;
use App\Repository\CtHistoriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_historique")
 */
class CtHistoriqueController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_historique_index", methods={"GET"})
     */
    public function index(CtHistoriqueRepository $ctHistoriqueRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctHistoriqueRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_historique/index.html.twig', [
            'ct_historiques' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_historique_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtHistoriqueRepository $ctHistoriqueRepository): Response
    {
        $ctHistorique = new CtHistorique();
        $form = $this->createForm(CtHistoriqueType::class, $ctHistorique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctHistoriqueRepository->add($ctHistorique, true);

            return $this->redirectToRoute('app_ct_historique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_historique/new.html.twig', [
            'ct_historique' => $ctHistorique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_historique_show", methods={"GET"})
     */
    public function show(CtHistorique $ctHistorique): Response
    {
        return $this->render('ct_historique/show.html.twig', [
            'ct_historique' => $ctHistorique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_historique_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtHistorique $ctHistorique, CtHistoriqueRepository $ctHistoriqueRepository): Response
    {
        $form = $this->createForm(CtHistoriqueType::class, $ctHistorique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctHistoriqueRepository->add($ctHistorique, true);

            return $this->redirectToRoute('app_ct_historique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_historique/edit.html.twig', [
            'ct_historique' => $ctHistorique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_historique_delete", methods={"POST"})
     */
    public function delete(Request $request, CtHistorique $ctHistorique, CtHistoriqueRepository $ctHistoriqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctHistorique->getId(), $request->request->get('_token'))) {
            $ctHistoriqueRepository->remove($ctHistorique, true);
        }

        return $this->redirectToRoute('app_ct_historique_index', [], Response::HTTP_SEE_OTHER);
    }
}
