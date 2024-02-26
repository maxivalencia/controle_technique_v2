<?php

namespace App\Controller;

use App\Entity\CtAutreVente;
use App\Form\CtAutreVenteType;
use App\Repository\CtAutreVenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_autre_vente")
 */
class CtAutreVenteController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_autre_vente_index", methods={"GET"})
     */
    public function index(CtAutreVenteRepository $ctAutreVenteRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctAutreVenteRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_autre_vente/index.html.twig', [
            'ct_autre_ventes' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_autre_vente_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtAutreVenteRepository $ctAutreVenteRepository): Response
    {
        $ctAutreVente = new CtAutreVente();
        $form = $this->createForm(CtAutreVenteType::class, $ctAutreVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctAutreVenteRepository->add($ctAutreVente, true);

            return $this->redirectToRoute('app_ct_autre_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_autre_vente/new.html.twig', [
            'ct_autre_vente' => $ctAutreVente,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_autre_vente_show", methods={"GET"})
     */
    public function show(CtAutreVente $ctAutreVente): Response
    {
        return $this->render('ct_autre_vente/show.html.twig', [
            'ct_autre_vente' => $ctAutreVente,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_autre_vente_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtAutreVente $ctAutreVente, CtAutreVenteRepository $ctAutreVenteRepository): Response
    {
        $form = $this->createForm(CtAutreVenteType::class, $ctAutreVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctAutreVenteRepository->add($ctAutreVente, true);

            return $this->redirectToRoute('app_ct_autre_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_autre_vente/edit.html.twig', [
            'ct_autre_vente' => $ctAutreVente,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_autre_vente_delete", methods={"POST"})
     */
    public function delete(Request $request, CtAutreVente $ctAutreVente, CtAutreVenteRepository $ctAutreVenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctAutreVente->getId(), $request->request->get('_token'))) {
            $ctAutreVenteRepository->remove($ctAutreVente, true);
        }

        return $this->redirectToRoute('app_ct_autre_vente_index', [], Response::HTTP_SEE_OTHER);
    }
}
