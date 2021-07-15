<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'input field-input',
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'input field-input',
                    ],
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'input field-input',
                    ],
                ]
            )
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'input field-input',
                    ],
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'input field-input',
                    ],
                ]
            )
            ->add('pictureName',
                FileType::class,
                [
                    'mapped' => false,
                    'attr' => [
                        'class' => 'file-input',
                        'name' => 'pictureName'
                    ],
                    'constraints' => [
                        new Image(
                            [
                                'maxSize' => '7000k',
                                'mimeTypesMessage' => "Le format n'est pas autorisé !",
                            ]
                        ),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
