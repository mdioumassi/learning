<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\Parameter;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('title')
           ->add('title', EntityType::class, [
               'label' => "Titre de la formation",
               'placeholder' => 'Choisir une formation',
               'class' => Parameter::class,
               'group_by' => 'categories',
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('p')
                       ->where("p.categories IN ('DEBUTANT', 'AVANCE', 'EXPERT')")
                       ->orderBy('p.categories', 'DESC');
               },
               'choice_label' => 'label',
               'multiple'  => false
           ])
//            ->add('start', DateTimeType::class, [
//                'date_widget' => 'single_text'
//            ])
//            ->add('end', DateTimeType::class, [
//                'date_widget' => 'single_text'
//            ])
            ->add('start', TextType::class)
            ->add('end', TextType::class)
            ->add('description')
//            ->add('all_day')
            ->add('background_color', ColorType::class)
            ->add('border_color', ColorType::class)
            ->add('text_color', ColorType::class);
//            ->add('ajouter', SubmitType::class, [
//                'attr' => [
//                    'class' => 'btn btn-primary'
//                ]
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
