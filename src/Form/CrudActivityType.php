<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CrudActivityType extends AbstractType
{
    private Security $s;

    public function __construct(Security $s) {
        $this->s = $s;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'name' ,
                TextType::class,
                [//pour les filtres
                    'label' => false,
                    'attr' =>
                        [
                            'placeholder' => 'nom de sortie',
                            'class'=> 'input'
                        ]
                ]
            )
            ->add('activityInfo',
                TextType::class,
                [
                    'label' => false,
                    'attr' =>
                        [
                            'placeholder' => 'description',
                            'class'=> 'input'
                        ]
                ]
            )
            ->add('startDateTime',
                DateTimeType::class,
                [
                    'label' => false,
                    'date_widget' => 'single_text',
                    'attr' => [
                            'class'=> 'input',
                    ],


                ]
            )
            ->add('inscriptionLimitDate',
                DateType::class,
                [
                    'label' => false,
                    'widget' => 'single_text',
                    'attr' =>
                        [
                            'class'=> 'input'
                        ]
                ]
            )
            ->add('maxInscriptionsNb',
                IntegerType::class,
                [
                    'label' => false,
                    'attr' => [
                        'min' => '3',
                        'max' => '60',
                        'class'=> 'input',
                    ]
                ]
            )
            ->add('location',
                EntityType::class,
                [
                    'class' => Location::class,
                    'choice_label' => 'name',
                    'label' => false,
                    'attr' => ['class' => 'select'],
                ]
            )
            ->add('campus',
                TextType::class,
                [
                    'label' => false,
                    'data' => $this->s->getUser()->getCampus()->getName(),
                    'disabled' => true,
                    'attr' => [
                        'class' => 'input',


                    ],
                ]
            )
            ->add('duration',
                IntegerType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'input',
                        'min' => '3',
                        'max' => '60',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
