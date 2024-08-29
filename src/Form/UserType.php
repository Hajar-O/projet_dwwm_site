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
            ])
            ->add('pseudo', TextType::class,[
                'label' => 'Pseudo',
                'attr' => [
                    'placeholder' => ' ',
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
