<?php

namespace App\Controller;

use App\Entity\CtUser;
use App\Form\CtUserType;
use App\Repository\CtUserRepository;
use App\Entity\CtRole;
use App\Form\CtRoleType;
use App\Repository\CtRoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/ct_app_administration")
 */
class CtAppAdministrationController extends AbstractController
{
    /**
     * @Route("/", name="app_ct_app_administration")
     */
    public function index(): Response
    {
        return $this->render('ct_app_administration/index.html.twig', [
            'controller_name' => 'CtAppAdministrationController',
        ]);
    }

    /**
     * @Route("/liste_verificateur", name="app_ct_app_administration_liste_verificateur", methods={"GET"})
     */
    public function ListeVerificateur(CtUserRepository $ctUserRepository, CtRoleRepository $ctRoleRepository): Response
    {
        /* $role = $ctRoleRepository->findOneBy(['role_name' => 'VERIFICATEUR']);
        $liste = $ctUserRepository->findBy(['ct_role_id' => $role]); */
        $liste = $ctUserRepository->findByVerificateur();
        return $this->render('ct_app_administration/liste_verificateur.html.twig', [
            'ct_users' => $liste,
        ]);
    }

    /**
     * @Route("/liste_secretaire", name="app_ct_app_administration_liste_secretaire", methods={"GET"})
     */
    public function ListeSecretaire(CtUserRepository $ctUserRepository, CtRoleRepository $ctRoleRepository): Response
    {
        //$roles = $ctRoleRepository->findOneBy(['role_name' => ['CONSTATATION', 'RECEPTION', 'VISITE']]);
        //$secretaire = new ArrayCollection();
        //$roles = $ctRoleRepository->findOneBy(['role_name' => 'VISITE']);
        //$liste = $ctUserRepository->findBy(['ct_role_id' => $roles]);
        $liste = $ctUserRepository->findBySecretaire();
        //$secretaire->add($liste);
        return $this->render('ct_app_administration/liste_secretaire.html.twig', [
            'ct_users' => $liste,
        ]);
    }
    
    /**
     * @Route("/liste_chef_de_centre", name="app_ct_app_administration_liste_chef_de_centre", methods={"GET"})
     */
    public function ListeChefDeCentre(CtUserRepository $ctUserRepository, CtRoleRepository $ctRoleRepository): Response
    {
        /* $role = $ctRoleRepository->findOneBy(['role_name' => 'CHEF_DE_CENTRE']);
        $liste = $ctUserRepository->findBy(['ct_role_id' => $role]); */
        $liste = $ctUserRepository->findByChefDeCentre();
        return $this->render('ct_app_administration/liste_chef_de_centre.html.twig', [
            'ct_users' => $liste,
        ]);
    }
    
    /**
     * @Route("/liste_regisseur", name="app_ct_app_administration_liste_regisseur", methods={"GET"})
     */
    public function ListeRegisseur(CtUserRepository $ctUserRepository, CtRoleRepository $ctRoleRepository): Response
    {
        /* $role = $ctRoleRepository->findOneBy(['role_name' => 'REGISSEUR']);
        $liste = $ctUserRepository->findBy(['ct_role_id' => $role]); */
        $liste = $ctUserRepository->findByRegisseur();
        return $this->render('ct_app_administration/liste_regisseur.html.twig', [
            'ct_users' => $liste,
        ]);
    }
    
    /**
     * @Route("/creer_regisseur", name="app_ct_app_administration_creer_regisseur", methods={"GET", "POST"})
     */
    public function CreerRegisseur(Request $request, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_regisseur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/creer_regisseur.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/creer_chef_de_centre", name="app_ct_app_administration_creer_chef_de_centre", methods={"GET", "POST"})
     */
    public function CreerChefDeCentre(Request $request, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_chef_de_centre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/creer_chef_de_centre.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/creer_verificateur", name="app_ct_app_administration_creer_verificateur", methods={"GET", "POST"})
     */
    public function CreerVerificateur(Request $request, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_verificateur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/creer_verificateur.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/creer_secretaire", name="app_ct_app_administration_creer_secretaire", methods={"GET", "POST"})
     */
    public function CreerSecretaire(Request $request, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_secretaire', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/creer_secretaire.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/voir_regisseur/{id}", name="app_ct_app_administration_voir_regisseur", methods={"GET"})
     */
    public function VoirRegisseur(CtUser $ctUser): Response
    {
        return $this->render('ct_app_administration/voir_regisseur.html.twig', [
            'ct_user' => $ctUser,
        ]);
    }
    
    /**
     * @Route("/voir_chef_de_centre/{id}", name="app_ct_app_administration_voir_chef_de_centre", methods={"GET"})
     */
    public function VoirChefDeCentre(CtUser $ctUser): Response
    {
        return $this->render('ct_app_administration/voir_chef_de_centre.html.twig', [
            'ct_user' => $ctUser,
        ]);
    }
    
    /**
     * @Route("/voir_verificateur/{id}", name="app_ct_app_administration_voir_verificateur", methods={"GET"})
     */
    public function VoirVerificateur(CtUser $ctUser): Response
    {
        return $this->render('ct_app_administration/voir_verificateur.html.twig', [
            'ct_user' => $ctUser,
        ]);
    }
    
    /**
     * @Route("/voir_secretaire/{id}", name="app_ct_app_administration_voir_secretaire", methods={"GET"})
     */
    public function VoirSecretaire(CtUser $ctUser): Response
    {
        return $this->render('ct_app_administration/voir_secretaire.html.twig', [
            'ct_user' => $ctUser,
        ]);
    }
    
    /**
     * @Route("/edit_regisseur/{id}", name="app_ct_app_administration_edit_regisseur", methods={"GET", "POST"})
     */
    public function EditRegisseur(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_regisseur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/edit_regisseur.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/edit_chef_de_centre/{id}", name="app_ct_app_administration_edit_chef_de_centre", methods={"GET", "POST"})
     */
    public function EditChefDeCentre(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_chef_de_centre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/edit_chef_de_centre.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/edit_verificateur/{id}", name="app_ct_app_administration_edit_verificateur", methods={"GET", "POST"})
     */
    public function EditVerificateur(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_verificateur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/edit_verificateur.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/edit_secretaire/{id}", name="app_ct_app_administration_edit_secretaire", methods={"GET", "POST"})
     */
    public function EditSecretaire(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository, UserPasswordHasherInterface $userPasswordHasher): Response
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

            return $this->redirectToRoute('app_ct_app_administration_liste_secretaire', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/edit_secretaire.html.twig', [
            'ct_user' => $ctUser,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/del_regisseur/{id}", name="app_ct_app_administration_del_regisseur", methods={"GET", "POST"})
     */
    public function DelRegisseur(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository): Response
    {
        $ctUserRepository->remove($ctUser, true);

        return $this->redirectToRoute('app_ct_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/del_chef_de_centre/{id}", name="app_ct_app_administration_del_chef_de_centre", methods={"GET", "POST"})
     */
    public function DelChefDeCentre(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository): Response
    {
        $ctUserRepository->remove($ctUser, true);

        return $this->redirectToRoute('app_ct_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/del_verificateur/{id}", name="app_ct_app_administration_del_verificateur", methods={"GET", "POST"})
     */
    public function DelVerificateur(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository): Response
    {
        $ctUserRepository->remove($ctUser, true);

        return $this->redirectToRoute('app_ct_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/del_secretaire/{id}", name="app_ct_app_administration_del_secretaire", methods={"GET", "POST"})
     */
    public function DelSecretaire(Request $request, CtUser $ctUser, CtUserRepository $ctUserRepository): Response
    {
        $ctUserRepository->remove($ctUser, true);

        return $this->redirectToRoute('app_ct_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/liste_role", name="app_ct_app_administration_liste_role", methods={"GET"})
     */
    public function ListeRole(CtRoleRepository $ctRoleRepository): Response
    {
        $liste = $ctRoleRepository->findAll();
        return $this->render('ct_app_administration/liste_role.html.twig', [
            'ct_roles' => $liste,
        ]);
    }
    
    /**
     * @Route("/creer_role", name="app_ct_app_administration_creer_role", methods={"GET", "POST"})
     */
    public function CreerRole(Request $request, CtRoleRepository $ctRoleRepository): Response
    {
        $ctRole = new CtRole();
        $form = $this->createForm(CtRoleType::class, $ctRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctRoleRepository->add($ctRole, true);

            return $this->redirectToRoute('app_ct_app_administration_liste_role', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/creer_role.html.twig', [
            'ct_role' => $ctRole,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/voir_role/{id}", name="app_ct_app_administration_voir_role", methods={"GET"})
     */
    public function VoirRole(CtRole $ctRole): Response
    {
        return $this->render('ct_app_administration/voir_role.html.twig', [
            'ct_role' => $ctRole,
        ]);
    }
    
    /**
     * @Route("/edit_role/{id}", name="app_ct_app_administration_edit_role", methods={"GET", "POST"})
     */
    public function EditRole(Request $request, CtRole $ctRole, CtRoleRepository $ctRoleRepository): Response
    {
        $form = $this->createForm(CtUserType::class, $ctRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ctRoleRepository->add($ctRole, true);

            return $this->redirectToRoute('app_ct_app_administration_liste_role', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ct_app_administration/edit_role.html.twig', [
            'ct_role' => $ctRole,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/del_role/{id}", name="app_ct_app_administration_del_role", methods={"GET", "POST"})
     */
    public function DelRole(Request $request, CtRole $ctRole, CtRoleRepository $ctRoleRepository): Response
    {
        $ctRoleRepository->remove($ctRole, true);

        return $this->redirectToRoute('app_ct_app_administration_liste_role', [], Response::HTTP_SEE_OTHER);
    }
}
