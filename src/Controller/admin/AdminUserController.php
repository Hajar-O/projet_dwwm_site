<?php
namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/admin-list', name: 'admin_list', methods: ['GET'])]
    public function listAdmins(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users/admin-list.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/admin/add-admin', name: 'add-admin')]
    public function insertNewAdmin( EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $admin = new User();
        $adminForm = $this->createForm(UserType::class, $admin);

        $adminForm->handleRequest($request);

        if ($adminForm->isSubmitted() && $adminForm->isValid()) {

            $clearPassword = $adminForm->get('password')->getData(); //je récupère le mot de passe en clair
            $hashedPassword = $passwordHasher->hashPassword($admin, $clearPassword); // je hache le mdp
            $admin->setPassword($hashedPassword); // j'associe et enregistre le mdp à l'admin

            $admin->setRoles(['ROLE_ADMIN']);
            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('sucess', 'Nouvel administrateur créé !');
            return $this->redirectToRoute('admin_list');
        }
        return $this->render('admin/users/insert-admin.html.twig', [
            'adminForm' => $adminForm->createView(),
        ]);

    }

    #[Route('/admin/edit/{id}', name: 'edit-admin')]
    public function editAdmin( EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher, $id)
    {
        $admin = $entityManager->getRepository(User::class)->find($id);

        if (!$admin) {
            throw $this->createNotFoundException('Administrateur non trouvé.');
        }

        $adminForm = $this->createForm(UserType::class, $admin);
        $adminForm->handleRequest($request);

        if ($adminForm->isSubmitted() && $adminForm->isValid()) {

            $clearPassword = $adminForm->get('password')->getData();
            if ($clearPassword) {
                // je hache et mets à J le mdp si modifié
                $hashedPassword = $passwordHasher->hashPassword($admin, $clearPassword);
                $admin->setPassword($hashedPassword);
            }

            $admin->setRoles(['ROLE_ADMIN']);
            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('succes', 'Administarteur mis à jour avec succès !');
            return $this->redirectToRoute('admin_list');

        }
        return $this->render('admin/users/edit-admin.html.twig', [
            'adminForm' => $adminForm->createView(),
        ]);
    }
}