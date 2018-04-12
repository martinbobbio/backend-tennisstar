<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class RequestFriendType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('user_send','entity',array('class' => 'BackendBundle:User','choice_label' => 'getUsername', 'label'=> 'Usuario que mando la solicitud'))
        ->add('user_receive','entity',array('class' => 'BackendBundle:User','choice_label' => 'getUsername', 'label'=> 'Usuario que recibio la solicitud'))
        ->add('status', CheckboxType::class, array('label'=> 'Acepto la solicitud?', 'required' => false,));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\RequestFriend'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_requestfriend';
    }


}