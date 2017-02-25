<?php

namespace ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

class AccountRegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', Type\EmailType::class)
            ->add('name', Type\TextType::class)
            ->add('password', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'first_options' => [ 'label' => 'Password', ],
                'second_options' => [ 'label' => 'Repeat password', ],
            ])
        ;
    }
}
