<?php

namespace App\Controller;

use App\Entity\CtGenre;
use App\Form\CtGenreType;
use App\Repository\CtGenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/ct_genre")
 */
class CtGenreController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_genre_index", methods={"GET"})
     */
    public function index(CtGenreRepository $ctGenreRepository): Response
    {
        return $this->render('ct_genre/index.html.twig', [
            'ct_genres' => $ctGenreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_genre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtGenreRepository $ctGenreRepository): Response
    {
        $ctGenre = new CtGenre();
        $form = $this->createForm(CtGenreType::class, $ctGenre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctGenreRepository->add($ctGenre, true);

            return $this->redirectToRoute('app_ct_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_genre/new.html.twig', [
            'ct_genre' => $ctGenre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_genre_show", methods={"GET"})
     */
    public function show(CtGenre $ctGenre): Response
    {
        return $this->render('ct_genre/show.html.twig', [
            'ct_genre' => $ctGenre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_genre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtGenre $ctGenre, CtGenreRepository $ctGenreRepository): Response
    {
        $form = $this->createForm(CtGenreType::class, $ctGenre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctGenreRepository->add($ctGenre, true);

            return $this->redirectToRoute('app_ct_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_genre/edit.html.twig', [
            'ct_genre' => $ctGenre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_genre_delete", methods={"POST"})
     */
    public function delete(Request $request, CtGenre $ctGenre, CtGenreRepository $ctGenreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctGenre->getId(), $request->request->get('_token'))) {
            $ctGenreRepository->remove($ctGenre, true);
        }

        return $this->redirectToRoute('app_ct_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
