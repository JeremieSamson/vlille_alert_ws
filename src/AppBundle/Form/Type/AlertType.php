<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Type\Base\FormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertType extends FormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', TimeType::class, array(
                'attr' => array(
                    'class'  => 'tismepicker',
                )
            ))
            ->add('end', TimeType::class, array(
                'attr' => array(
                    'class'  => 'timsepicker',
                )
            ))
            ->add('station', EntityType::class, array(
                'required' => false,
                'class' => 'AppBundle:Station',
                'attr' => array(
                    "class" => "select2"
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Alert',
                'csrf_protection'   => false
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form_alert';
    }
}