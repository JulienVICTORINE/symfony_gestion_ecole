<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Etudiant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'attr' => ['max' => (new \DateTime())->format('Y-m-d')],
            ])
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    'Licence 1' => 'L1',
                    'Licence 2' => 'L2',
                    'Licence 3' => 'L3',
                    'Master 1' => 'M1',
                    'Master 2' => 'M2',
                ],
                'placeholder' => 'Choisir un niveau',
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true, // si true alors on ordonne la sélection des cours avec des checkboxes
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
