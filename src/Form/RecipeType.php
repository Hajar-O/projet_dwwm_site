<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('description',CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#000000'
                )
            ))

            ->add('image')
            ->add('isPublished')
            ->add('idCategory', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'type',
            ])
           /* ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
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
