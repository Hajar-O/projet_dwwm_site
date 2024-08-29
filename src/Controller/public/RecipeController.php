<?php

namespace App\Controller\public;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function showRecipe(int $id,RecipeIngredientRepository $ingredientRepository,CommentRepository $commentRepository, RecipeRepository $recipeRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $recipe = $recipeRepository->find($id);
        $ingredients = $ingredientRepository->findAll();

        if (!$recipe) {
            $html404 = $this->renderView('public/error/404.html.twig');
            return new Response($html404, 404);
        }

        //je récupère l'user connecté

        $user = $this->getUser();

        $review = new Comment;
        $review->setIdRecipe($recipe);
        $review->setIdUser($user);
        $review->setPublishedAt(new \DateTimeImmutable('now'));

        //je créé un formulaire de commentaire

        $reviewsForm = $this->createForm(CommentType::class, $review);
        $reviewsForm->handleRequest($request);

        if($reviewsForm->isSubmitted() && $reviewsForm->isValid()){
            $entityManager->persist($review);
            $entityManager->flush();

            //redirection pour éviter la redirection
            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        $reviews = $commentRepository->findBy(['idRecipe'=> $recipe]);

        return $this->render('public/recipe/recipe-show.html.twig', [
            'recipe' => $recipe,
            'ingredients' => $recipe->getRecipeIngredients(),
            'reviews' => $reviews,
            'reviewsForm'=> $reviewsForm
        ]);
    }


}