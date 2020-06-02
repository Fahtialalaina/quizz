<?php

namespace App\Form;

use App\Entity\Questions;
use App\Entity\Competences;
use App\Entity\Types;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QuestionsEditType extends AbstractType
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
                    'placeholder' => 'Texte complementaire...'
                ]
            ])
            ->add('autreTexte', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Autre texte...'
                ]
            ])
            //->add('users')
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
               //->add('etat', )
               ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Nom valider' => '0',
                    'Valider' => '1',
                    'Réffuser' => '2',
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            
            //->add('etat', )
            ->add('motif', TextareaType::class, [
                'empty_data'    =>  null,
                'required'      =>  FALSE,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Motif...'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label'=>'Modifier', 
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
