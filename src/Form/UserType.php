<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();
        
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => ' ',
                ],
            ])
            //->add('roles')
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => ' ',
                ],
                'required' => false,
                'data' => null,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le mot de passe ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule et un chiffre.',
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class,[
                'label' => 'Pseudo',
                'attr' => [
                    'placeholder' => ' ',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le pseudo ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le pseudo ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9_]{3,20}$/',
                        'message' => 'Le pseudo doit comporter entre 3 et 20 caractères, et ne contenir que des lettres, des chiffres et des tirets bas.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'S\'inscrire',
            ])
        ;
        if ($currentUser && in_array('ROLE_ADMIN', $currentUser->getRoles())) {

            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                ],

                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Choisir un rôle',
            ]);

            $builder->get('roles')->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) > 0 ? $rolesArray[0] : null;
                },
                function ($roleString) {
                    return [$roleString];
                }
            ));}}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
