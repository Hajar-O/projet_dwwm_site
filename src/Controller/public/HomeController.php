<?php
//création d'un namespace (le chemin) qui indique l'emplacement des class.
namespace App\Controller\public;
// On appelle le namespace des class utilisées afin que Symfony fasse le require de ces dernières.

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

//On étand la class AbstractController qui permet d'utiliser les fontions utilitaires pour les controllers.

class HomeController extends AbstractController{
    #[Route('/', name: 'home')]
    public function findAllrecipe(RecipeRepository $recipeRepository){
        $recipes = $recipeRepository->findAll();
        $getLastRecipe = $recipeRepository->getLastRecipe();

        $randomRecipe = [];

        if (!empty($recipes)) {
            $randomRecipe = array_rand($recipes, min(4, count($recipes)));
            $randomRecipe = array_map(fn($key) => $recipes[$key], $randomRecipe);
        }
        return $this->render('public/home.html.twig',[
            'recipes' => $recipes,
            'lastRecipe' => $getLastRecipe,
            'randomRecipes' => $randomRecipe
        ]);
    }


}