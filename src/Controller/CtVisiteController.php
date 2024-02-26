<?php

namespace App\Controller;

use App\Entity\CtVisite;
use App\Form\CtVisiteType;
use App\Repository\CtVisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_visite")
 */
class CtVisiteController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_visite_index", methods={"GET"})
     */
    public function index(CtVisiteRepository $ctVisiteRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctVisiteRepository->findBy([], ["id" => "DESC"],[10,100]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_visite/index.html.twig', [
            'ct_visites' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_visite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtVisiteRepository $ctVisiteRepository): Response
    {
        $ctVisite = new CtVisite();
        $form = $this->createForm(CtVisiteType::class, $ctVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctVisiteRepository->add($ctVisite, true);

            return $this->redirectToRoute('app_ct_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_visite/new.html.twig', [
            'ct_visite' => $ctVisite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_visite_show", methods={"GET"})
     */
    public function show(CtVisite $ctVisite): Response
    {
        return $this->render('ct_visite/show.html.twig', [
            'ct_visite' => $ctVisite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_visite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtVisite $ctVisite, CtVisiteRepository $ctVisiteRepository): Response
    {
        $form = $this->createForm(CtVisiteType::class, $ctVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctVisiteRepository->add($ctVisite, true);

            return $this->redirectToRoute('app_ct_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_visite/edit.html.twig', [
            'ct_visite' => $ctVisite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_visite_delete", methods={"POST"})
     */
    public function delete(Request $request, CtVisite $ctVisite, CtVisiteRepository $ctVisiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctVisite->getId(), $request->request->get('_token'))) {
            $ctVisiteRepository->remove($ctVisite, true);
        }

        return $this->redirectToRoute('app_ct_visite_index', [], Response::HTTP_SEE_OTHER);
    }
}
