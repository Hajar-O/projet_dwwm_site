<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'class' => 'reviewArea',
                    'placeholder' => 'Mon commentaire...'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le commentaire ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 500,
                        'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractère.',
                        'maxMessage' => 'Le commentaire ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    /*new Assert\Regex([
                        'pattern' => '/^[\p{L}\p{N}\p{P}\p{S}\p{Z}]*$/u',
                        'message' => 'Le commentaire contient des caractères non autorisés.',
                    ]),*/
                ],
            ])
            /*->add('publishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('idUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('idRecipe', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'id',
            ])*/
            ->add('Save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btnEnvoye'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
