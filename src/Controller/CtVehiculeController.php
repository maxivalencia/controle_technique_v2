<?php

namespace App\Controller;

use App\Entity\CtVehicule;
use App\Form\CtVehiculeType;
use App\Repository\CtVehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_vehicule")
 */
class CtVehiculeController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_vehicule_index", methods={"GET"})
     */
    public function index(CtVehiculeRepository $ctVehiculeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $ctVehiculeRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );
        return $this->render('ct_vehicule/index.html.twig', [
            'ct_vehicules' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_ct_vehicule_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $ctVehicule = new CtVehicule();
        $form = $this->createForm(CtVehiculeType::class, $ctVehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctVehiculeRepository->add($ctVehicule, true);

            return $this->redirectToRoute('app_ct_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_vehicule/new.html.twig', [
            'ct_vehicule' => $ctVehicule,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_vehicule_show", methods={"GET"})
     */
    public function show(CtVehicule $ctVehicule): Response
    {
        return $this->render('ct_vehicule/show.html.twig', [
            'ct_vehicule' => $ctVehicule,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_vehicule_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtVehicule $ctVehicule, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        $form = $this->createForm(CtVehiculeType::class, $ctVehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctVehiculeRepository->add($ctVehicule, true);

            return $this->redirectToRoute('app_ct_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_vehicule/edit.html.twig', [
            'ct_vehicule' => $ctVehicule,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_vehicule_delete", methods={"POST"})
     */
    public function delete(Request $request, CtVehicule $ctVehicule, CtVehiculeRepository $ctVehiculeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctVehicule->getId(), $request->request->get('_token'))) {
            $ctVehiculeRepository->remove($ctVehicule, true);
        }

        return $this->redirectToRoute('app_ct_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
}
