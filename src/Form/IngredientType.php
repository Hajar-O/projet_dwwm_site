<?php

namespace App\Form;

use App\Entity\CategoryIngredient;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CategoryIngredient', EntityType::class, [
                'class' => CategoryIngredient::class,
                'choice_label' => 'name',
            ])
            ->add('ingredient')
           /* ->add('recipes', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
