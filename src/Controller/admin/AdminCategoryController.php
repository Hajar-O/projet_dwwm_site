<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminCategoryController extends AbstractController{
    #[Route('/admin/category-list', name: 'admin_category')]
    public function listCategories(CategoryRepository $categoryRepository){

        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/category-list.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/category-delete/{id}', name: 'admin_category_delete')]
    public function deleteCategory(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager )
    {
        $category = $categoryRepository->find($id);

       try{
           $entityManager->remove($category);
           $entityManager->flush();

           $this->addFlash('success', 'La categorie à bien été supprimer');
       } catch (\Exception $e){
           $this->addFlash('error', 'Une erreur est survenue lors de la suppression');
       }

            return $this->redirectToRoute('admin_category');
    }

    #[Route('/admin/category-add', name: 'admin_category_add')]
    public function insertCategory(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        // on a créé une classe de "gabarit de formulaire HTML" avec php bin/console make:form

        $category = new Category();

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();


            $this->addFlash('sucess', 'La catégorie à bien été ajouté.');
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/category/category-insert.html.twig', [
            'categoryForm' => $categoryForm->createView(),
        ]);
    }

    #[Route('/admin/category-update/{id}', name: 'admin_category_update')]
    public function updateCategory(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);
        $categoryUpdateForm = $this->createForm(CategoryType::class, $category);
        $categoryUpdateForm->handleRequest($request);

        if($categoryUpdateForm->isSubmitted() && $categoryUpdateForm->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('succes', 'La catégorie à bien été mise à jour.');
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/category/category-update.html.twig', [
            'categoryUpdateForm' => $categoryUpdateForm->createView(),
        ]);
    }

}