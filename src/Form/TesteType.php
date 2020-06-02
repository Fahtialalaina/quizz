<?php

namespace App\Form;

use App\Entity\Competences;
use App\Entity\Questions;
use App\Entity\Types;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TesteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competences', EntityType::class, array(
                'class'         =>  Competences::class,
                'choice_label'  =>  'title',
                'multiple'      =>  FALSE,
                'expanded'      =>  FALSE,
                'placeholder'   =>  '---- Choissiz Competence ----',
                'empty_data'    =>  null,
                'required'      =>  FALSE,
                'attr' => [
                    'class' => 'form-control',
                ]
            ))
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre du question...'
                ]
            ])
            // ->add('attached')
            ->add('texteComplementaire', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Texte complémentaire...'
                ]
            ])
            ->add('autreTexte', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Autre texte...'
                ]
            ])
            ->add('types', EntityType::class, [
                'class' => Types::class,
                'choice_label' => 'title',
                'multiple'      =>  true,
                'expanded'      =>  false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // import une image dans nos base de donnéd
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'empty_data'    =>  null,
                'required'      =>  FALSE,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            // ->add('title', TextType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //         'placeholder' => 'Réponse ...'
            //     ]
                
            // ])
            ->add('isAnswer', CheckboxType::class, [
                'attr' => [
                    // 'class' => 'form-control',
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label'=>'Valider', 
                'attr'=>['class'=>'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
