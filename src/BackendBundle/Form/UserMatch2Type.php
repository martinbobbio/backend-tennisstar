<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;



class UserMatch2Type extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
        ->add('user', EntityType::class, 
        array(
            'class' => 'BackendBundle:User', 
            'label' => 'Equipo 1:',
            'choice_label' => function($user){
                return $user->getUsername();     
            },
            'multiple'  => true,
            'required' => true,
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {     
                return $er->createQueryBuilder('u');
            },
        ))
        ->add('user2', EntityType::class, 
        array(
            'class' => 'BackendBundle:User', 
            'label' => 'Equipo 2:',
            'choice_label' => function($user){
                return $user->getUsername();     
            },
            'multiple'  => true,
            'required' => true,
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {     
                return $er->createQueryBuilder('u');
            },
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\UserMatch'
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