<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email', 'class' => 'main-input', 'placeholder' => 'Adresse mail'],
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse mail n\'est pas valide.'
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                'attr' => ['autocomplete' => 'username', 'class' => 'main-input', 'placeholder' => 'Nom d\'utilisateur'],
                'constraints' => [
                    new NotBlank(['message' => 'Merci de renseigner un nom d\'utilisateur']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre nom d\'utilisateur est trop court.',
                        'maxMessage' => 'Votre nom d\'utilisateur est trop grand.',
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions générales.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'main-input', 'placeholder' => 'Mot de passe'],
                'constraints' => [
                    new Regex('/^(?:.*)[A-Z]+(?:.*)$/', "Le mot de passe doit contenir au moins une majuscule"),
                    new Regex('/^(?:.*)\d+(?:.*)$/', "Le mot de passe doit contenir au moins un chiffre"),
                    new Regex('/^(?:.*)[^a-z\d]+(?:.*)$/i', "Le mot de passe doit contenir au moins un caractère spécials"),
                    new NotBlank(['message' => 'Merci de renseigner un mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => true,
            'data_class' => User::class,
        ]);
    }
}
