<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Parameter;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un Email.',
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
//            ->add('password', PasswordType::class, [
//                // instead of being set onto the object directly,
//                // this is read and encoded in the controller
//                'mapped' => false,
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Veuillez saisir un mot de passe.',
//                    ]),
//                    new Length([
//                        'min' => 6,
//                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
//                        // max length allowed by Symfony for security reasons
//                        'max' => 4096,
//                    ]),
//                ],
//            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un prénom.',
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nom de famille.',
                    ]),
                ]
            ])
//            ->add('address', TextType::class, [
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Veuillez saisir une adresse.',
//                    ]),
//                ]
//            ])
//            ->add('address_complement', TextType::class, [
//                'required' => false
//            ])
//            ->add('postal_code', IntegerType::class, [
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Veuillez saisir un code postal.',
//                    ]),
//                    new Length([
//                        'min' => 5,
//                        'minMessage' => 'Le code postal doit comporter {{ limit }} chiffre',
//                        // max length allowed by Symfony for security reasons
//                        'max' => 5,
//                    ]),
//                ]
//            ])
//            ->add('city', TextType::class, [
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Veuillez saisir une ville.',
//                    ]),
//                ]
//            ])
            ->add('phone_mobile', TelType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un numéro de téléphone',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Le numéro de téléphone doit comporter {{ limit }} chiffre',
                        ///max length allowed by Symfony for security reasons
                        'max' => 10,
                    ]),
                ]
            ])
            ->add('civility', EntityType::class, [
                'placeholder' => 'Choisir',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir une civilité.',
                    ]),
                ],
                'class' => Parameter::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where("p.categories = 'CIVILITE'");
                },
                'choice_label' => 'label'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
