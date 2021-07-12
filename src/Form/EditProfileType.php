<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input field-input',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input field-input',
                ]
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input field-input',
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input field-input',
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input field-input',
                ]
            ])

//            ->add('plainPassword', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'first_options' => [
//                    'label' => false,
//                    'attr' => [
//                        'autocomplete' => 'new-password',
//                        'class' => 'input is-rounded'
//                        ],
//                    'constraints' => [
////                        new NotBlank([
////                            'message' => 'Please enter a password',
////                        ]),
//                        new Length([
//                            'min' => 6,
//                            'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
//                            // max length allowed by Symfony for security reasons
//                            'max' => 4096,
//                        ]),
//                    ],
////                    'label' => 'Nouveau mot de passe',
//                ],
//                'second_options' => [
//                    'label' => false,
//                    'attr' => ['autocomplete' => 'new-password',
//                        'class' => 'input is-rounded'
//                    ],
////                    'label' => 'Confirmer le nouveau mot de passe',
//                ],
//                'invalid_message' => 'Veuillez entrer le même mot de passe.',
//                // Instead of being set onto the object directly,
//                // this is read and encoded in the controller
//                'mapped' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
