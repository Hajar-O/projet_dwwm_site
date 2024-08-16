<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
                'label' => 'Published', // Tu peux personnaliser le label ici si besoin
                'required' => false,    // Ajoute cette option si la case à cocher n'est pas obligatoire
            ])
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
