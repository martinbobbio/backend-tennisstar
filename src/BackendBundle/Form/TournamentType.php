<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;


class TournamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, array('label'=> 'Nombre', 'required' => true,))
        ->add('creator','entity',array('class' => 'BackendBundle:User','choice_label' => 'getUsername', 'label'=> 'Creador del partido'))
        ->add('dateTournament', DateType::class, array(
            'widget' => 'single_text',
            'html5' => false,
            'label' => 'Fecha del torneo',
            'attr' => ['class' => 'js-datepicker',  'placeholder' => 'Elegir fecha'],
        ))
        ->add('dateHour', TimeType::class, array(
            'input'  => 'datetime',
            'widget' => 'choice',
            'label' => 'Hora del torneo',
        ))
        ->add('count', ChoiceType::class, array(
            'choices'  => array(                                             
                16 => 'Torneo grande (16 jugadores)',
                8 => 'Torneo mediano (8 jugadores)',
                4 => 'Torneo mediano (4 jugadores)',
            ),'label' => 'Límite de jugadores'
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Tournament'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_tournament';
    }


}