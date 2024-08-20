<?php

namespace App\Controller\admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryIngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminRecipeController extends AbstractController
{
    #[Route('/admin/recipe-list', name: 'admin_recipe')]
    public function listRecipes(RecipeRepository $recipeRepository)
    {
        $recipe = $recipeRepository->findAll();
            return $this->render('admin/recipe/recipe-list.html.twig', [
                'recipes' => $recipe
            ]);
    }

    #[Route('/recipe-show/{id}', name: 'admin_recipe_show')]
    public function showRecipe(int $id, RecipeRepository $recipeRepository)
    {
        $recipe = $recipeRepository->find($id);
        return $this->render('public/recipe/recipe-show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/admin/recipe-add', name: 'admin_recipe_add')]
    public function  addRecipe(CategoryIngredientRepository $categoryIngredientRepository,EntityManagerInterface $entityManager, Request $request)
    {
        // on a créé une classe de "gabarit de formulaire HTML" avec php bin/console make:form

        $CategoryIng = $categoryIngredientRepository->findAll();
        //dd($CategoryIng);
        //$ingredient = $ingredientRepository->findAll();
        //je crée une classe de l'entité Recipe.
        $recipe = new Recipe();

        $recipeForm = $this->createForm(RecipeType::class, $recipe);

        $recipeForm->handleRequest($request);

        if($recipeForm->isSubmitted() && $recipeForm->isValid()){
            //Je stocke dans $recipeIngredient un tableau, et je le clone.
            $recipeIngredients = clone $recipe->getRecipeIngredients();
            //Pour chaque tableau de
            foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
                $recipe->removeRecipeIngredient($recipeIngredient);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            foreach ($recipeIngredients as $recipeIngredient) {
                $recipeIngredient->setRecipe($recipe);
                $entityManager->persist($recipeIngredient);
                $entityManager->flush();
            }

            $this->addFlash('success', 'la recette à bien été ajouté');

            return $this->redirectToRoute('admin_recipe');
        }
            return $this->render('admin/recipe/recipe-add.html.twig', [
                'recipeForm' => $recipeForm->createView(),
                'categoryIngredients' => $CategoryIng,
                //'ingredients' => $ingredient
            ]);
        }

        #[Route('/admin/recipe-edit/{id}', name: 'admin_recipe_edit')]
    public function upDateRecipe(int $id, RecipeRepository $recipeRepository,CategoryIngredientRepository $categoryIngredientRepository, EntityManagerInterface $entityManager, Request $request)
    {

        $recipe = $recipeRepository->find($id);
        $categoryIng = $categoryIngredientRepository->findAll();
        $recipeUpdateForm = $this->createForm(RecipeType::class, $recipe);

        $recipeUpdateForm->handleRequest($request);

        if($recipeUpdateForm->isSubmitted() && $recipeUpdateForm->isValid()){
            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'La recette à bien été mise à jour.');
            return $this->redirectToRoute('admin_recipe');
        }

        return $this->render('admin/recipe/recipe-edit.html.twig', [
            'recipeForm' => $recipeUpdateForm->createView(),
            'categoryIngredients' => $categoryIng
        ]);
    }

    #[Route('/admin/recipe-delete/{id}', name: 'admin_recipe_delete')]
    public function deleteRecipe(int $id, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager)
    {

        $recipe = $recipeRepository->find($id);

        try{
            $entityManager->remove($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'la rectte à bien été supprimer');
            return $this->redirectToRoute('admin_recipe');
        } catch(\Exception $e){
            return $this->render('admin/recipe/delete.html.twig', [
                'recipe' => $recipe
            ]);
        }

    }


}