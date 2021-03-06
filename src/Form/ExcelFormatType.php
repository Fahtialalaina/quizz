<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExcelFormatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('format', ChoiceType::class, [
                'choices' => [
                    'xlsx' => 'xlsx',
                    'ods' => 'ods',
                    'csv' => 'csv',
                ],
                'label' => false,
                'placeholder' => 'Select a format',
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Envoyer',
                'attr'=>['class'=>'btn btn-default'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
