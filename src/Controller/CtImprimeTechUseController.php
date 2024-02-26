<?php

namespace App\Controller;

use App\Entity\CtImprimeTechUse;
use App\Form\CtImprimeTechUseType;
use App\Repository\CtImprimeTechUseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_imprime_tech_use")
 */
class CtImprimeTechUseController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_imprime_tech_use_index", methods={"GET"})
     */
    public function index(CtImprimeTechUseRepository $ctImprimeTechUseRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctImprimeTechUseRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_imprime_tech_use/index.html.twig', [
            'ct_imprime_tech_uses' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_imprime_tech_use_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $ctImprimeTechUse = new CtImprimeTechUse();
        $form = $this->createForm(CtImprimeTechUseType::class, $ctImprimeTechUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctImprimeTechUseRepository->add($ctImprimeTechUse, true);

            return $this->redirectToRoute('app_ct_imprime_tech_use_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_imprime_tech_use/new.html.twig', [
            'ct_imprime_tech_use' => $ctImprimeTechUse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_imprime_tech_use_show", methods={"GET"})
     */
    public function show(CtImprimeTechUse $ctImprimeTechUse): Response
    {
        return $this->render('ct_imprime_tech_use/show.html.twig', [
            'ct_imprime_tech_use' => $ctImprimeTechUse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_imprime_tech_use_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtImprimeTechUse $ctImprimeTechUse, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        $form = $this->createForm(CtImprimeTechUseType::class, $ctImprimeTechUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctImprimeTechUseRepository->add($ctImprimeTechUse, true);

            return $this->redirectToRoute('app_ct_imprime_tech_use_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_imprime_tech_use/edit.html.twig', [
            'ct_imprime_tech_use' => $ctImprimeTechUse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_imprime_tech_use_delete", methods={"POST"})
     */
    public function delete(Request $request, CtImprimeTechUse $ctImprimeTechUse, CtImprimeTechUseRepository $ctImprimeTechUseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctImprimeTechUse->getId(), $request->request->get('_token'))) {
            $ctImprimeTechUseRepository->remove($ctImprimeTechUse, true);
        }

        return $this->redirectToRoute('app_ct_imprime_tech_use_index', [], Response::HTTP_SEE_OTHER);
    }
}
