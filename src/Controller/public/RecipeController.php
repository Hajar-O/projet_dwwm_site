<?php

namespace App\Controller\public;
use App\Repository\CategoryRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class RecipeController extends AbstractController
{
    #[Route('/recipe-list', name: 'recipe-list')]
    public function index(RecipeRepository $recipeRepository)
    {

        $recipes = $recipeRepository->findAll();
        return $this->render('public/recipe/recipe-list.html.twig', [
            'recipes' => $recipes
        ]);
    }



    #[Route('/recipe/salted', name: 'recipe-salted')]
    public function showSaltedRecipes( RecipeRepository $recipeRepository, CategoryRepository $categoryRepository): Response
    {

        $categoryType = $categoryRepository->findOneById(1);

        if($categoryType){
            $recipes =  $recipeRepository->RecipesByCategory($categoryType);

            return $this->render('public/recipe/recipe-salted.html.twig', [
                'recipes' => $recipes,
                'categoryType' => $categoryType
            ]);
        }
        return $this->render('public/error/404.html.twig');

    }

    #[Route('/recipe/sweet', name: 'recipe-sweet')]
    public function showSweetRecipes( RecipeRepository $recipeRepository, CategoryRepository $categoryRepository): Response
    {

        $categoryType = $categoryRepository->findOneById(2);

        if($categoryType){
            $recipes =  $recipeRepository->RecipesByCategory($categoryType);

            return $this->render('public/recipe/recipe-sweet.html.twig', [
                'recipes' => $recipes,
                'categoryType' => $categoryType
            ]);
        }
        return $this->render('public/error/404.html.twig');

    }

    #[Route('/recipe/all/{id}', name: 'recipe')]
    public function showRecipe(int $id,RecipeIngredientRepository $ingredientRepository, RecipeRepository $recipeRepository)
    {
        $recipe = $recipeRepository->find($id);
        $ingredients = $ingredientRepository->findAll();
        return $this->render('public/recipe/recipe-show.html.twig', [
            'recipe' => $recipe,
            'ingredients' => $recipe->getRecipeIngredients()
        ]);
    }
}