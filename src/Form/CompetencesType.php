<?php

namespace App\Form;

use App\Entity\Families;
use App\Entity\Competences;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            // ->add('families', EntityType::class, [
            //     'class' => Families::class,
            //     'placeholder' => ' --- Selectionner Articles ---',
            //     'mapped' => false,
            //     'choice_label'  =>  'title',
            // ])
            ->add('families', EntityType::class, array(
                'class'         =>  Families::class,
                'choice_label'  =>  'title',
                'multiple'      =>  FALSE,
                'expanded'      =>  FALSE,
                'placeholder'   =>  '---- Choissiz Article ----',
                'empty_data'    =>  null,
                'required'      =>  FALSE,
                'attr' => [
                    'class' => 'form-control',
                ]
            ))
        ;

        //AJOUTER UN EVENEMENT DANS MON FOCTION
        $builder->get('families')->AddEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event){
                $form = $event->getForm();

                //dd($form->getData());

                // $form->add('families', EntityType::class, [
                //     'class' => Families::class,
                //     'placeholder' => ' --- Selectionner Articles ---',
                //     'choices' => $form->getData()->getEnfant()
                // ]);

            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competences::class,
        ]);
    }
}
