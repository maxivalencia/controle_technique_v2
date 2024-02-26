<?php

namespace App\Controller;

use App\Entity\CtBordereau;
use App\Form\CtBordereauType;
use App\Repository\CtBordereauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_bordereau")
 */
class CtBordereauController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_bordereau_index", methods={"GET"})
     */
    public function index(CtBordereauRepository $ctBordereauRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctBordereauRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_bordereau/index.html.twig', [
            'ct_bordereaus' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_bordereau_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtBordereauRepository $ctBordereauRepository): Response
    {
        $ctBordereau = new CtBordereau();
        $form = $this->createForm(CtBordereauType::class, $ctBordereau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctBordereauRepository->add($ctBordereau, true);

            return $this->redirectToRoute('app_ct_bordereau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_bordereau/new.html.twig', [
            'ct_bordereau' => $ctBordereau,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_bordereau_show", methods={"GET"})
     */
    public function show(CtBordereau $ctBordereau): Response
    {
        return $this->render('ct_bordereau/show.html.twig', [
            'ct_bordereau' => $ctBordereau,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_bordereau_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtBordereau $ctBordereau, CtBordereauRepository $ctBordereauRepository): Response
    {
        $form = $this->createForm(CtBordereauType::class, $ctBordereau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctBordereauRepository->add($ctBordereau, true);

            return $this->redirectToRoute('app_ct_bordereau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_bordereau/edit.html.twig', [
            'ct_bordereau' => $ctBordereau,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_bordereau_delete", methods={"POST"})
     */
    public function delete(Request $request, CtBordereau $ctBordereau, CtBordereauRepository $ctBordereauRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctBordereau->getId(), $request->request->get('_token'))) {
            $ctBordereauRepository->remove($ctBordereau, true);
        }

        return $this->redirectToRoute('app_ct_bordereau_index', [], Response::HTTP_SEE_OTHER);
    }
}
