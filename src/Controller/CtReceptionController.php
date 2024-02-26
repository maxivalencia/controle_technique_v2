<?php

namespace App\Controller;

use App\Entity\CtReception;
use App\Form\CtReceptionType;
use App\Repository\CtReceptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_reception")
 */
class CtReceptionController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_reception_index", methods={"GET"})
     */
    public function index(CtReceptionRepository $ctReceptionRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctReceptionRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_reception/index.html.twig', [
            'ct_receptions' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_reception_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtReceptionRepository $ctReceptionRepository): Response
    {
        $ctReception = new CtReception();
        $form = $this->createForm(CtReceptionType::class, $ctReception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctReceptionRepository->add($ctReception, true);

            return $this->redirectToRoute('app_ct_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_reception/new.html.twig', [
            'ct_reception' => $ctReception,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_reception_show", methods={"GET"})
     */
    public function show(CtReception $ctReception): Response
    {
        return $this->render('ct_reception/show.html.twig', [
            'ct_reception' => $ctReception,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_reception_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtReception $ctReception, CtReceptionRepository $ctReceptionRepository): Response
    {
        $form = $this->createForm(CtReceptionType::class, $ctReception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctReceptionRepository->add($ctReception, true);

            return $this->redirectToRoute('app_ct_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_reception/edit.html.twig', [
            'ct_reception' => $ctReception,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_reception_delete", methods={"POST"})
     */
    public function delete(Request $request, CtReception $ctReception, CtReceptionRepository $ctReceptionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctReception->getId(), $request->request->get('_token'))) {
            $ctReceptionRepository->remove($ctReception, true);
        }

        return $this->redirectToRoute('app_ct_reception_index', [], Response::HTTP_SEE_OTHER);
    }
}
