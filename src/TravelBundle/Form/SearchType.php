<?php

namespace TravelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', CountryType::class)
            ->add('city', TextType::class)
            ->add('capacity', ChoiceType::class, array(
                    'choices' => array(
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5,
                        '6' => 6,
                        '7' => 7,
                        '8' => 8,
                        '9' => 9,
                        '10' => 10
                    )
                )
            )
            ->add('startDate', DateType::class, array(
                'widget' => 'choice',
                 'placeholder' => array(
                    'year' => 'Year',
                     'month' => 'Month',
                     'day' => 'Day'
                  ))
            )
            ->add('endDate', DateType::class, array(
                'widget' => 'choice',
                'placeholder' => array(
                    'year' => 'Year',
                    'month' => 'Month',
                    'day' => 'Day'
                )
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TravelBundle\Entity\Search'
        ));
    }

}
