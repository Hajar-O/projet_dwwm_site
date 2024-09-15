<?php
namespace App\Controller\public;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/inscription', name: 'add-user')]
    public function insertUser(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {

        $user = new User();
        $userForm = $this->createForm(UserType::class,$user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){

            $existingUser= $userRepository->findOneBy(['email' => $userForm->get('email')->getData()]);

            if($existingUser){

                $this->addFlash('error','Il existe déjà un compte pour cette adresse mail');

                return $this->redirectToRoute('add-user');
            }
            $clearPassword = $userForm->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
            $user->setPassword($hashedPassword);

            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription enregistrée');

        return $this->redirectToRoute('app_login'); // Redirection après l'ajout du flash

        }

        return $this->render('public/users/form-inscription.html.twig', [
            'userForm' => $userForm->createView(),
        ]);

    }
    #[Route('/user/profil/{id}', name: 'profil-user')]
    public function profil(int $id, UserRepository $userRepository,)
    {
        $user = $userRepository->find($id);
        return $this->render('public/users/profil.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/user/edit_profil/{id}', name: 'edit-user')]
    public function editUser(int $id, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('compte non trouvé.');
        }

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $clearPassword = $userForm->get('password')->getData();
            if ($clearPassword) {
                // je hache et mets à J le mdp si modifié
                $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
                $user->setPassword($hashedPassword);
            }

            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('succes', 'Mise à jour réussi !');
            return $this->redirectToRoute('profil-user', ['id' => $user->getId()]);

        }
        return $this->render('public/users/edit-user-profil.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

}