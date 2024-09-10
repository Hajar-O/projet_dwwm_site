<?php

namespace App\Controller\admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryIngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
   /* public function  addRecipe(SluggerInterface $slugger, ParameterBagInterface $params, CategoryIngredientRepository $categoryIngredientRepository,EntityManagerInterface $entityManager, Request $request)
    {
        // on a créé une classe de "gabarit de formulaire HTML" avec php bin/console make:form

        $CategoryIng = $categoryIngredientRepository->findAll();

        $recipe = new Recipe();

        $recipeForm = $this->createForm(RecipeType::class, $recipe);

        $recipeForm->handleRequest($request);

        if ($recipeForm->isSubmitted() && $recipeForm->isValid()) {

            //récupère le fichier et  le nom du fichier

            $imageFile = $recipeForm->get('image')->getData();

            // si il y a bien un fichier envoyé
            if ($imageFile) {
                //je récupère son nom
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // je nettoie le nom (sort les caractères spéciaux etc)

                $safeFilename = $slugger->slug($originalFilename);
                // Je rajoute un identifiant unnique au nom
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    //je récupère le chemin de la racine du projet
                    $rootPath = $params->get('kernel.project_dir');

                    // je déplace le fichier dans le dossier /public/upload en partant de la racine
                    // du projet, et je renomme le fichier avec le nouveau nom (slugifié et identifiant unique)
                    $imageFile->move($rootPath . '/public/assets/uploads', $newFilename);
                } catch (FileException $e) {
                    dd($e->getMessage());
                }
                // je stocke dans la propriété image
                // de l'entité article le nom du fichier
                $recipe->setImage($newFilename);

            }

                //je récupère les ingrédients associés à la recette (collection d'objets RecipeIngredient), puis en fait une clône pour les manipulations ultérieures.
                $recipeIngredients = clone $recipe->getRecipeIngredients();
                //Je boucle sur mes ingrédients associés à la recette
                foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
                    //retire les ingrédients de la recette pour les réasigner après persisté la recette.
                    $recipe->removeRecipeIngredient($recipeIngredient);
                }

                $entityManager->persist($recipe);
                $entityManager->flush();

                //je boucle sur chaque ingrédient clôné précédemment.
                foreach ($recipeIngredients as $recipeIngredient) {
                    //je réassocie les ingrédients à la new instance Recipe persisté
                    $recipeIngredient->setRecipe($recipe);
                    //Prépare chaque ingrédient à la sauvgarde en BDD
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
        }*/
    public function new(SluggerInterface $slugger, ParameterBagInterface $params, Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            // si il y a bien un fichier envoyé
            if ($imageFile) {
                //je récupère son nom
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // je nettoie le nom (sort les caractères spéciaux etc)

                $safeFilename = $slugger->slug($originalFilename);
                // Je rajoute un identifiant unnique au nom
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    //je récupère le chemin de la racine du projet
                    $rootPath = $params->get('kernel.project_dir');

                    // je déplace le fichier dans le dossier /public/upload en partant de la racine
                    // du projet, et je renomme le fichier avec le nouveau nom (slugifié et identifiant unique)
                    $imageFile->move($rootPath . '/public/assets/uploads', $newFilename);
                } catch (FileException $e) {
                    dd($e->getMessage());
                }
                // je stocke dans la propriété image
                // de l'entité article le nom du fichier
                $recipe->setImage($newFilename);

                $entityManager->persist($recipe);
                $entityManager->flush();
            }
            $this->addFlash('success', 'la recette à bien été ajouté');

            return $this->redirectToRoute('admin_recipe', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('admin/recipe/recipe-add.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }



        #[Route('/admin/recipe-edit/{id}', name: 'admin_recipe_edit')]
   /* public function upDateRecipe(int $id,SluggerInterface $slugger, ParameterBagInterface $params, RecipeRepository $recipeRepository,CategoryIngredientRepository $categoryIngredientRepository, EntityManagerInterface $entityManager, Request $request)
    {

        $recipe = $recipeRepository->find($id);
        $existingImage = $recipe->getImage();
        $categoryIng = $categoryIngredientRepository->findAll();
        $recipeUpdateForm = $this->createForm(RecipeType::class, $recipe);

        $originalIngredients= new ArrayCollection();
        foreach($recipe->getRecipeIngredients() as $ingredient){
            $originalIngredients->add($ingredient);
        }

        $recipeUpdateForm->handleRequest($request);

        if($recipeUpdateForm->isSubmitted() && $recipeUpdateForm->isValid()){

            $imageFile = $recipeUpdateForm->get('image')->getData();

            if ($imageFile) {
                //je récupère son nom
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // je nettoie le nom (sort les caractères spéciaux etc)

                $safeFilename = $slugger->slug($originalFilename);
                // Je rajoute un identifiant unnique au nom
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    //je récupère le chemin de la racine du projet
                    $rootPath = $params->get('kernel.project_dir');

                    // je déplace le fichier dans le dossier /public/upload en partant de la racine
                    // du projet, et je renomme le fichier avec le nouveau nom (slugifié et identifiant unique)
                    $imageFile->move($rootPath . '/public/assets/uploads', $newFilename);
                } catch (FileException $e) {
                    dd($e->getMessage());
                }
                // je stocke dans la propriété image
                // de l'entité article le nom du fichier
                $recipe->setImage($newFilename);
            } else{
                // Si aucun fichier n'est uploadé, conserver l'image existante
                $recipe->setImage($existingImage);
            }

            // Supprimer les ingrédients qui ne sont plus présents dans le formulaire
            foreach ($originalIngredients as $originalIngredient) {

                if (false === $recipe->getRecipeIngredients()->contains($originalIngredient)) {
                    // Supprimer l'ingrédient de la recette et de la base de données
                    $recipe->removeRecipeIngredient($originalIngredient);
                    $entityManager->remove($originalIngredient);
                }
            }

            //Je boucle sur tous les ingrédients associé à la recette
            foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
                //j'associe les ingrédients à la recette acctuelle
                $recipeIngredient->setRecipe($recipe);
                //je demande a EntityManager de préparer la sauvgarde des Ingrédients en bdd
                $entityManager->persist($recipeIngredient);
            }
            //je demande a EntityManager de préparer la sauvgarde de la Recipe en bdd
            $entityManager->persist($recipe);
            //j'exécute la sauvgarde des ingrédients et de la Recipe
            $entityManager->flush();



            $this->addFlash('success', 'La recette à bien été mise à jour.');
            return $this->redirectToRoute('admin_recipe');
        }

        return $this->render('admin/recipe/recipe-edit.html.twig', [
            'recipeForm' => $recipeUpdateForm->createView(),
            'categoryIngredients' => $categoryIng
        ]);
    }*/

        public function edit(SluggerInterface $slugger, ParameterBagInterface $params, Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
        {
            $existingImage = $recipe->getImage();
            $originalRecipeIngredients = new ArrayCollection();

            // Create an ArrayCollection of the current RecipeIngredient objects in the database
            foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
                $originalRecipeIngredients->add($recipeIngredient);
            }

            $form = $this->createForm(RecipeType::class, $recipe);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $imageFile = $form->get('image')->getData();

                if ($imageFile) {
                    //je récupère son nom
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                    // je nettoie le nom (sort les caractères spéciaux etc)

                    $safeFilename = $slugger->slug($originalFilename);
                    // Je rajoute un identifiant unnique au nom
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        //je récupère le chemin de la racine du projet
                        $rootPath = $params->get('kernel.project_dir');

                        // je déplace le fichier dans le dossier /public/upload en partant de la racine
                        // du projet, et je renomme le fichier avec le nouveau nom (slugifié et identifiant unique)
                        $imageFile->move($rootPath . '/public/assets/uploads', $newFilename);
                    } catch (FileException $e) {
                        dd($e->getMessage());
                    }
                    // je stocke dans la propriété image
                    // de l'entité article le nom du fichier
                    $recipe->setImage($newFilename);
                } else{
                    // Si aucun fichier n'est uploadé, conserver l'image existante
                    $recipe->setImage($existingImage);
                }
                // Remove the relationship between the RecipeIngredient and the Recipe
                foreach ($originalRecipeIngredients as $recipeIngredient) {
                    if (false === $recipe->getRecipeIngredients()->contains($recipeIngredient)) {
                        // Remove the RecipeIngredient from the Recipe

                        $recipe->getRecipeIngredients()->removeElement($recipeIngredient);
                        // If it was a many-to-one relationship, remove the relationship like this
                        $recipeIngredient->setRecipe(null);

                        $entityManager->persist($recipeIngredient);

                        // If you wanted to delete the Tag entirely, you can also do that
                        $entityManager->remove($recipeIngredient);
                    }
                }

                $entityManager->persist($recipe);
                $entityManager->flush();

                $this->addFlash('success', 'La recette à bien été mise à jour.');
                return $this->redirectToRoute('admin_recipe', [], Response::HTTP_SEE_OTHER);

            }

            return $this->render('admin/recipe/recipe-edit.html.twig', [
                'recipe' => $recipe,
                'form' => $form,
            ]);
        }
    #[Route('/admin/recipe-delete/{id}', name: 'admin_recipe_delete')]
    public function deleteRecipe(int $id, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager) : Response
    {

        $recipe = $recipeRepository->find($id);

        if(!$recipe) {
            $html404 = $this->renderView('admin/error/404.html.twig');
            return new Response($html404, 404);
        }

        try{

            $entityManager->remove($recipe);
            $entityManager->flush();


            $this->addFlash('success', 'la rectte à bien été supprimer');
        } catch(\Exception $exception){
            return $this->render('admin/error/500.html.twig', [
                'error' => $exception->getMessage()
            ]);
        }
        return $this->redirectToRoute('admin_recipe');

    }


}