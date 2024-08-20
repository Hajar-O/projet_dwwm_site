<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Measure;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity')
            /* ->add('recipe', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'title',
            ])*/
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'ingredient',
            ])
            ->add('measure', EntityType::class, [
                'class' => Measure::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
        ]);
    }
}
