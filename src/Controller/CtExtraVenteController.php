<?php

namespace App\Controller;

use App\Entity\CtExtraVente;
use App\Form\CtExtraVenteType;
use App\Repository\CtExtraVenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_extra_vente")
 */
class CtExtraVenteController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_extra_vente_index", methods={"GET"})
     */
    public function index(CtExtraVenteRepository $ctExtraVenteRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctExtraVenteRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_extra_vente/index.html.twig', [
            'ct_extra_ventes' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_extra_vente_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtExtraVenteRepository $ctExtraVenteRepository): Response
    {
        $ctExtraVente = new CtExtraVente();
        $form = $this->createForm(CtExtraVenteType::class, $ctExtraVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctExtraVenteRepository->add($ctExtraVente, true);

            return $this->redirectToRoute('app_ct_extra_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_extra_vente/new.html.twig', [
            'ct_extra_vente' => $ctExtraVente,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_extra_vente_show", methods={"GET"})
     */
    public function show(CtExtraVente $ctExtraVente): Response
    {
        return $this->render('ct_extra_vente/show.html.twig', [
            'ct_extra_vente' => $ctExtraVente,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_extra_vente_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtExtraVente $ctExtraVente, CtExtraVenteRepository $ctExtraVenteRepository): Response
    {
        $form = $this->createForm(CtExtraVenteType::class, $ctExtraVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctExtraVenteRepository->add($ctExtraVente, true);

            return $this->redirectToRoute('app_ct_extra_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_extra_vente/edit.html.twig', [
            'ct_extra_vente' => $ctExtraVente,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_extra_vente_delete", methods={"POST"})
     */
    public function delete(Request $request, CtExtraVente $ctExtraVente, CtExtraVenteRepository $ctExtraVenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctExtraVente->getId(), $request->request->get('_token'))) {
            $ctExtraVenteRepository->remove($ctExtraVente, true);
        }

        return $this->redirectToRoute('app_ct_extra_vente_index', [], Response::HTTP_SEE_OTHER);
    }
}
