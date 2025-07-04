<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Etudiant;
use App\Entity\Professeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('nombreHeures', IntegerType::class, [
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('professeur', EntityType::class, [
                'class' => Professeur::class,
                'choice_label' => 'nomcomplet',
                'placeholder' => 'Choisir un professeur',
                'required' => true,
            ])
            ->add('etudiants', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => 'nomcomplet',
                'multiple' => true,
                'expanded' => true, // si true alors on sélectionne les étudiants avec les checkboxes, sinon (false) pour une liste déroulante
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
