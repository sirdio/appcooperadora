<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class UsuarioEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni', TextType::class, array('required'=>'required', 'attr'=>array('class'=>'form_dni')))
            ->add('apellido', TextType::class, array('required'=>'required', 'attr'=>array('class'=>'form-control form_ape')))
            ->add('nombre', TextType::class, array('required'=>'required', 'attr'=>array('class'=>'form-control form_nomb')))
            ->add('email', EmailType::class, array('required'=>'required', 'attr'=>array('class'=>'form-control form_email')))
            ->add('telefono', TextType::class, array('required'=>'required', 'attr'=>array('class'=>'form-control form_tel')))
            ->add('Guardar', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Usuario'
        ));
    }
}