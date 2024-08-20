<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('publishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('time')
            ->add('recipeIngredients', CollectionType::class, [
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                //'by_reference' => false,

                //'choice_label' => 'ingredient',

            ])
            ->add('description',CKEditorType::class, [
 /*               'config' => [
                    'config_name' => 'default',
                    'uiColor' => '#1c1c1c',
                    'toolbar' => 'full',
                    'attr' => ['id'=> 'editor']

                ],*/
            ])
            ->add('image')
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Published',
                'required' => false,    // option si la case à cocher non obligatoire
            ])
            ->add('idCategory', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'type',
            ])

            /* ->add('recipeIngredients', EntityType::class, [
                'class' => RecipeIngredient::class,
                'choice_label' => 'ingredient',
                'multiple'=> true,
            ])*/
            ->add('save', SubmitType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
