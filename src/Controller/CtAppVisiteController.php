<?php

namespace App\Controller;

use App\Entity\CtTypeVisite;
use App\Form\CtTypeVisiteType;
use App\Repository\CtTypeVisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ct_app_visite")
 */
class CtAppVisiteController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_visite")
     */
    public function index(): Response
    {
        return $this->render('ct_app_visite/index.html.twig', [
            'controller_name' => 'CtAppVisiteController',
        ]);
    }
    
    /**
     * @Route("/liste_type", name="app_ct_app_visite_liste_type", methods={"GET"})
     */
    public function ListeType(CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        return $this->render('ct_app_visite/liste_type.html.twig', [
            'ct_type_visites' => $ctTypeVisiteRepository->findAll(),
            'total' => count($ctTypeVisiteRepository->findAll()),
        ]);
    }
    
    /**
     * @Route("/creer_type", name="app_ct_app_visite_creer_type", methods={"GET", "POST"})
     */
    public function CreerType(Request $request, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        $ctTypeVisite = new CtTypeVisite();
        $form = $this->createForm(CtTypeVisiteType::class, $ctTypeVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeVisiteRepository->add($ctTypeVisite, true);

            return $this->redirectToRoute('app_ct_app_visite_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_visite/creer_type.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/voir_type/{id}", name="app_ct_app_visite_voir_type", methods={"GET"})
     */
    public function VoirType(CtTypeVisite $ctTypeVisite): Response
    {
        return $this->render('ct_app_visite/voir_type.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
        ]);
    }
    
    /**
     * @Route("/edit_type/{id}", name="app_ct_app_visite_edit_type", methods={"GET", "POST"})
     */
    public function EditType(Request $request, CtTypeVisite $ctTypeVisite, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        //$ctTypeVisite = new CtTypeVisite();
        $form = $this->createForm(CtTypeVisiteType::class, $ctTypeVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctTypeVisiteRepository->add($ctTypeVisite, true);

            return $this->redirectToRoute('app_ct_app_visite_liste_type', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_visite/edit_type.html.twig', [
            'ct_type_visite' => $ctTypeVisite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("//del_type/{id}", name="app_ct_app_visite_del_type", methods={"GET", "POST"})
     */
    public function delete(Request $request, CtTypeVisite $ctTypeVisite, CtTypeVisiteRepository $ctTypeVisiteRepository): Response
    {
        //if ($this->isCsrfTokenValid('delete'.$ctTypeVisite->getId(), $request->request->get('_token'))) {
            $ctTypeVisiteRepository->remove($ctTypeVisite, true);
        //}

        return $this->redirectToRoute('app_ct_app_visite_liste_type', [], Response::HTTP_SEE_OTHER);
    }
}
