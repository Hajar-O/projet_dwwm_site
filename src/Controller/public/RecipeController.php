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
    public function showRecipe(int $id,CommentRepository $commentRepository, RecipeRepository $recipeRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            $html404 = $this->renderView('public/error/404.html.twig');
            return new Response($html404, 404);
        }

        //je récupère l'user connecté

        $user = $this->getUser();

        $review = new Comment;
        //Associe la recette courante au commentaire
        $review->setIdRecipe($recipe);
        //Associe l'utilisateur connecté au commentaire
        $review->setIdUser($user);
        //Définit la date de publication du commentaire à la date et l'heure actuelles.
        $review->setPublishedAt(new \DateTimeImmutable('now'));

        //je créé un formulaire de commentaire
        // on a créé une classe de "gabarit de formulaire HTML" avec php bin/console make:form

        $reviewsForm = $this->createForm(CommentType::class, $review);
        //Cette méthode traite la requête HTTP
        $reviewsForm->handleRequest($request);

        if($reviewsForm->isSubmitted() && $reviewsForm->isValid()){
            //pérpare l'objet review à l'insertion en bdd
            $entityManager->persist($review);
            //execute l'insertion
            $entityManager->flush();

            //redirection vers la meme page pour éviter double soumission
            return $this->redirectToRoute('recipe', ['id' => $recipe->getId()]);
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