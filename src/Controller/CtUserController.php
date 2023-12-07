<?php

namespace App\Controller;

use App\Entity\CtUser;
use App\Form\CtUserType;
use App\Repository\CtUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * @Route("/ct_user")
 */
class CtUserController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_user_index", methods={"GET"})
     */
    public function index(CtUserRepository $ctUserRepository): Response
    {
        return $this->render('ct_user/index.html.twig', [
            'ct_users' => $ctUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ct_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $ctUser = new CtUser();
        $form = $this->createForm(CtUserType::class, $ctUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUser->setRoles(['ROLE_'.$ctUser->getCtRoleId()->getRoleName()]);
            $ctUser->setPassword($userPasswordHasher->hashPassword(
                $ctUser,
                $ctUser->getPassword()
            ));
            $ctUserRepository->add($ctUser, true);

            return $this->redirectToRoute('app_ct_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_user/new.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_user_show", methods={"GET"})
     */
    public function show(CtUser $ctUser): Response
    {
        return $this->render('ct_user/show.html.twig', [
            'ct_user' => $ctUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ct_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(CtUserType::class, $ctUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctUser->setRoles(['ROLE_'.$ctUser->getCtRoleId()->getRoleName()]);
            $ctUser->setPassword($userPasswordHasher->hashPassword(
                $ctUser,
                $ctUser->getPassword()
            ));
            $ctUserRepository->add($ctUser, true);

            return $this->redirectToRoute('app_ct_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_user/edit.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ct_user_delete", methods={"POST"})
     */
    public function delete(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ctUser->getId(), $request->request->get('_token'))) {
            $ctUserRepository->remove($ctUser, true);
        }

        return $this->redirectToRoute('app_ct_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
