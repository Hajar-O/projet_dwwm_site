<?php

namespace App\Controller\admin;

use App\Entity\Recipe;
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

    #[Route('/admin/recipe-add', name: 'admin_recipe_add')]
    public function  addRecipe(EntityManagerInterface $entityManager, Request $request)
    {

        // dans ton form twig, pour les ingrédients => récupérer tous les ingredients (inregredient repository)

        $recipe = null;

        if($request->isMethod('POST')) {

            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $image = $request->request->get('image');
            $time = $request->request->get('time');
            $ingredients = $request->request->get('ingredients');
            $mesure = $request->request->get('mesure');
            $quantity = $request->request->get('quantity');
            $gategory = $request->request->get('gategory');

            $recipe = new Recipe();

            //Je passe en paramètre les valeurs

            $recipe->setTitle($title);
            $recipe->setDescription($description);
            $recipe->setImage($image);
            $recipe->setTime($time);

        }
    }

    #[Route('/admin/recipe-delete/{id}', name: 'admin_recipe_delete')]
    public function deleteRecipe(int $id, RecipeRepository $recipeRepository ,EntityManagerInterface $entityManager)
    {
        $recipe = $recipeRepository->find($id);
        if(!$recipe){
            $html404 = $this->renderView('admin/error/404.html.twig');
            return new Response($html404, 404);
        }

        try{
            $entityManager->remove($recipe);
            $entityManager->flush();

        } catch(\Exception $e){
            return $this->render('admin/error/500.html.twig',[
                'error' => $e->getMessage()
                ]);
        }
        return $this->redirectToRoute('admin_recipe_list');
    }
}