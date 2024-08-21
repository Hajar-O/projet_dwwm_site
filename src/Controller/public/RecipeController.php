<?php

namespace App\Controller\public;
use App\Repository\CategoryRepository;
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

    #[Route('/recipe/{id}', name: 'recipe')]
    public function showRecipe(int $id, RecipeRepository $recipeRepository)
    {
        $recipe = $recipeRepository->find($id);
        return $this->render('recipe/recipe.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/recipe-slated', name: 'recipe')]
    public function showRecipeSlated( RecipeRepository $recipeRepository, CategoryRepository $categoryRepository): Response
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
}