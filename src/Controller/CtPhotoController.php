<?php

namespace App\Controller;

use App\Entity\CtPhoto;
use App\Form\CtPhotoType;
use App\Repository\CtPhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_photo")
 */
class CtPhotoController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_photo_index", methods={"GET"})
     */
    public function index(CtPhotoRepository $ctPhotoRepository): Response
    {
        return $this->render('ct_photo/index.html.twig', [
            'ct_photos' => $ctPhotoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_photo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtPhotoRepository $ctPhotoRepository): Response
    {
        $ctPhoto = new CtPhoto();
        $form = $this->createForm(CtPhotoType::class, $ctPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctPhotoRepository->add($ctPhoto, true);

            return $this->redirectToRoute('app_ct_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_photo/new.html.twig', [
            'ct_photo' => $ctPhoto,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_photo_show", methods={"GET"})
     */
    public function show(CtPhoto $ctPhoto): Response
    {
        return $this->render('ct_photo/show.html.twig', [
            'ct_photo' => $ctPhoto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_photo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtPhoto $ctPhoto, CtPhotoRepository $ctPhotoRepository): Response
    {
        $form = $this->createForm(CtPhotoType::class, $ctPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctPhotoRepository->add($ctPhoto, true);

            return $this->redirectToRoute('app_ct_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_photo/edit.html.twig', [
            'ct_photo' => $ctPhoto,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_photo_delete", methods={"POST"})
     */
    public function delete(Request $request, CtPhoto $ctPhoto, CtPhotoRepository $ctPhotoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctPhoto->getId(), $request->request->get('_token'))) {
            $ctPhotoRepository->remove($ctPhoto, true);
        }

        return $this->redirectToRoute('app_ct_photo_index', [], Response::HTTP_SEE_OTHER);
    }
}
