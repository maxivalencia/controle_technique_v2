<?php

namespace App\Controller;

use App\Entity\CtRole;
use App\Form\CtRoleType;
use App\Repository\CtRoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_role")
 */
class CtRoleController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_role_index", methods={"GET"})
     */
    public function index(CtRoleRepository $ctRoleRepository): Response
    {
        return $this->render('ct_role/index.html.twig', [
            'ct_roles' => $ctRoleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_role_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtRoleRepository $ctRoleRepository): Response
    {
        $ctRole = new CtRole();
        $form = $this->createForm(CtRoleType::class, $ctRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctRoleRepository->add($ctRole, true);

            return $this->redirectToRoute('app_ct_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_role/new.html.twig', [
            'ct_role' => $ctRole,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_role_show", methods={"GET"})
     */
    public function show(CtRole $ctRole): Response
    {
        return $this->render('ct_role/show.html.twig', [
            'ct_role' => $ctRole,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_role_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtRole $ctRole, CtRoleRepository $ctRoleRepository): Response
    {
        $form = $this->createForm(CtRoleType::class, $ctRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctRoleRepository->add($ctRole, true);

            return $this->redirectToRoute('app_ct_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_role/edit.html.twig', [
            'ct_role' => $ctRole,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_role_delete", methods={"POST"})
     */
    public function delete(Request $request, CtRole $ctRole, CtRoleRepository $ctRoleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctRole->getId(), $request->request->get('_token'))) {
            $ctRoleRepository->remove($ctRole, true);
        }

        return $this->redirectToRoute('app_ct_role_index', [], Response::HTTP_SEE_OTHER);
    }
}
