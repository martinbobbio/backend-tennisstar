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


class MatchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, array('label'=> 'Nombre', 'required' => true,))
        ->add('creator','entity',array('class' => 'BackendBundle:User','choice_label' => 'getUsername', 'label'=> 'Creador del partido'))
        ->add('dateMatch', DateType::class, array(
            'widget' => 'single_text',
            'html5' => false,
            'label' => 'Fecha del partido',
            'attr' => ['class' => 'js-datepicker',  'placeholder' => 'Elegir fecha'],
        ))
        ->add('dateHour', TimeType::class, array(
            'input'  => 'datetime',
            'widget' => 'choice',
            'label' => 'Hora del partido',
        ))
        ->add('type', ChoiceType::class, array(
            'choices'  => array(                                             
                'Singles' => 'Singles',
                'Dobles' => 'Dobles',
            ),'label' => 'Tipo de partido'
        ))
        ->add('isPrivate', CheckboxType::class, array('label'=> 'Partido privado? (Solo amigos)'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Match'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_match';
    }


}