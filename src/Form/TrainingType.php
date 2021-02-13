<?php

namespace App\Form;

use App\Entity\Parameter;
use App\Entity\Training;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', EntityType::class, [
                'required' => false,
                'label' => false,
                'placeholder' => 'Choisir une formation',
                'class' => Parameter::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where("p.categories = 'DEBUTANT'");
                },
                'choice_label' => 'label'
            ])
            ->add('label_avance', EntityType::class, [
                'required' => false,
                'label' => false,
                'placeholder' => 'Choisir une formation',
                'class' => Parameter::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where("p.categories = 'AVANCE'");
                },
                'choice_label' => 'label'
            ])
            ->add('label_expert', EntityType::class, [
                'label' => false,
                'required' => false,
                'placeholder' => 'Choisir une formation',
                'class' => Parameter::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where("p.categories = 'EXPERT'");
                },
                'choice_label' => 'label'
            ])
           // ->add('level')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
