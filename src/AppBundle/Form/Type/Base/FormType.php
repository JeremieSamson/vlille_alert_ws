<?php

namespace AppBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;

class FormType extends AbstractType
{
    public function getDateOptions($required = false){
        return array(
            'required' => $required,
            'widget' => 'single_text',
            'attr' => array(
                'class'  => 'datetime',
            )
        );
    }
}