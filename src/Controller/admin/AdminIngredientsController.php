<?php

namespace App\Controller\admin;

use App\Entity\Ingredients;
use App\Form\IngredientsType;
use App\Repository\IngredientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminIngredientsController extends AbstractController
{
    #[Route('/admin/ingredients-list', name: 'admin_ingredients')]
    public function listIngredients(IngredientsRepository $ingredientsRepository)
    {
        $ingredient = $ingredientsRepository->findAll();
            return $this->render('admin/recipe/ingredients-list.html.twig', [
                'ingredients' => $ingredient
            ]);
    }

    #[Route ('admin/ingredients-delete/{id}', name: 'admin_ingredients_delete')]
    public function deleteIngredients(IngredientsRepository $ingredientsRepository, int $id, EntityManagerInterface $entityManager)
    {
        $ingredient = $ingredientsRepository->find($id);

        try{
            $entityManager -> remove($ingredient);
            $entityManager->flush();
        } catch(\Exception $e){
            return $this->render('admin/error/error.html.twig', [
                'error' => $e->getMessage()
            ]);
        }
        return $this->redirectToRoute('admin_ingredients');
    }

    #[Route ('admin/ingredients-add', name: 'admin_ingredients_add')]
    public function insertIngredients(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator )
    {
        // on a créé une classe de "gabarit de formulaire HTML" avec php bin/console make:form

        // je créé une instance de la classe d'entité Ingredients
        $ingredients = new Ingredients();

        // permet de générer une instance de la classe de gabarit de formulaire
        // et de la lier avec l'instance de l'entité
        $ingredientsForm = $this->createForm(IngredientsType::class, $ingredients);

        // lie le formulaire avec la requête
        $ingredientsForm->handleRequest($request);

        // si le formulaire a été envoyé et que ces données sont correctes
        if ($ingredientsForm->isSubmitted() && $ingredientsForm->isValid()) {
            $entityManager->persist($ingredients);
            $entityManager->flush();

            $this->addFlash('success', 'l\'ingrédient à bien été ajouté');
            return $this->redirectToRoute('admin_ingredients');
        }
        return $this->render('admin/recipe/ingredients-insert.html.twig', [
            'ingredientsForm' => $ingredientsForm->createView()
        ]);


    }

    #[Route ('admin/ingredients/update/{id}', name: 'admin_ingredients_update-')]
    public function upDateIngredients( int $id,IngredientsRepository $ingredientsRepository ,Request $request, EntityManagerInterface $entityManager)
    {
        //je récupère l'ingrédient par l'id
        $ingredients= $ingredientsRepository->find($id);


        // je génére une instance de la classe de gabarit de formulaire
        //  et la lie avec l'instance de l'entité
        $ingredientsUpdateForm = $this->createForm(IngredientsType::class, $ingredients);

        //Je lie le formulaire à la requete.
        $ingredientsUpdateForm->handleRequest($request);

        // si le formulaire a été envoyé et que ces données sont correctes
        if($ingredientsUpdateForm->isSubmitted() && $ingredientsUpdateForm->isValid()){
            $entityManager->persist($ingredients);
            $entityManager->flush();

            return $this->redirectToRoute('admin_ingredients');
        }

        return $this->render('admin/recipe/ingredients-update.html.twig', [
            'ingredientsUpdateForm' => $ingredientsUpdateForm->createView()
        ]);

    }
}
