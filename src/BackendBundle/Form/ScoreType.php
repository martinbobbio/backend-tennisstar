<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class ScoreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
        ->add('first_set_j1', IntegerType::class, array('label'=> '1° Set - (Jugador 1)', 'required' => false,'attr' => array('min' => 0, 'max' => 7)))
        ->add('first_set_j2', IntegerType::class, array('label'=> '1° Set - (Jugador 2)', 'required' => false,'attr' => array('min' => 0, 'max' => 7)))
        ->add('second_set_j1', IntegerType::class, array('label'=> '2° Set - (Jugador 1)', 'required' => false,'attr' => array('min' => 0, 'max' => 7)))
        ->add('second_set_j2', IntegerType::class, array('label'=> '2° Set - (Jugador 2)', 'required' => false,'attr' => array('min' => 0, 'max' => 7)))
        ->add('third_set_j1', IntegerType::class, array('label'=> '3° Set - (Jugador 1)', 'required' => false,'attr' => array('min' => 0, 'max' => 7)))
        ->add('third_set_j2', IntegerType::class, array('label'=> '3° Set - (Jugador 2)', 'required' => false,'attr' => array('min' => 0, 'max' => 7)));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Score'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_score';
    }


}