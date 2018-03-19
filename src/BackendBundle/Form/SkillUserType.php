<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class SkillUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('gameStyle', ChoiceType::class, array(
            'choices'  => array(                                             
                'Jugador agresivo' => 'Jugador agresivo',
                'Jugador defensivo' => 'Jugador defensivo',
                'Jugador completo' => 'Jugador completo',
            ),'label' => 'Estilo de juego'
        ))
        ->add('gameLevel', ChoiceType::class, array(
            'choices'  => array(                                             
                'Principiante' => 'Principiante',
                'Amateur' => 'Amateur',
                'Profesional' => 'Profesional',
            ),'label' => 'Estilo de juego'
        ))
        ->add('typeBackhand', ChoiceType::class, array(
            'choices'  => array(                                             
                'A dos manos' => 'A dos manos',
                'A una mano' => 'A una mano',
            ),'label' => 'Tipo de reves'
        ))
        ->add('forehand', TextType::class, array(
            'attr' => array(
                'min' => 1,
                'max' => 100,
                'data-slider-id' => 'ex1Slider',
                'data-slider-min' => '1',
                'data-slider-max' => '100',
                'data-slider-step' => '1',
                'data-slider-value' => '0',
            ),'label' => 'Drive'
        ))

        ->add('backhand', TextType::class, array(
            'attr' => array(
                'min' => 1,
                'max' => 100,
                'data-slider-id' => 'ex2Slider',
                'data-slider-min' => '1',
                'data-slider-max' => '100',
                'data-slider-step' => '1',
                'data-slider-value' => '0',
            ),'label' => 'Revés'
        ))

        ->add('service', TextType::class, array(
            'attr' => array(
                'min' => 1,
                'max' => 100,
                'data-slider-id' => 'ex3Slider',
                'data-slider-min' => '1',
                'data-slider-max' => '100',
                'data-slider-step' => '1',
                'data-slider-value' => '0',
            ),'label' => 'Saque'
        ))

        ->add('volley', TextType::class, array(
            'attr' => array(
                'min' => 1,
                'max' => 100,
                'data-slider-id' => 'ex4Slider',
                'data-slider-min' => '1',
                'data-slider-max' => '100',
                'data-slider-step' => '1',
                'data-slider-value' => '0',
            ),'label' => 'Volea'
        ))

        ->add('resistence', TextType::class, array(
            'attr' => array(
                'min' => 1,
                'max' => 100,
                'data-slider-id' => 'ex5Slider',
                'data-slider-min' => '1',
                'data-slider-max' => '100',
                'data-slider-step' => '1',
                'data-slider-value' => '0',
            ),'label' => 'Resistencia'
        ));

        
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\SkillUser'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_skilluser';
    }


}