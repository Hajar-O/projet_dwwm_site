<?php
namespace App\Controller\public;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function insertUser(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher)
    {

        $user = new User();
        $userForm = $this->createForm(UserType::class,$user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){

            $clearPassword = $userForm->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
            $user->setPassword($hashedPassword);

            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

            //return $this->addFlash('succes', 'incription enregistré');
        }

        return $this->render('public/users/form-inscription.html.twig', [
            'userForm' => $userForm->createView(),
        ]);

    }
}