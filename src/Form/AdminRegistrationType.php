<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,[
            'label' => 'Ваше имя',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
            ->add('surname',TextType::class,[
                'label' => 'Ваша Фамилия',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email',EmailType::class, [
                'label' => 'Ваш Email',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('phone',TextType::class,[
                'label' => 'Ваш телефон',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('password',PasswordType::class,[
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'IDI VON!',
                'first_options'  => [
                    'label' => 'Password'
                ],
                'second_options' => [
                    'label' => 'Repeat Password'
                ],
            ])
            ->add('save',SubmitType::class,[
                'label' => 'Добавить и отправить приглашение на почту!',
                'attr' => [
                    'class' => 'btn btn-primary btn-in-form'
                ]
            ]);
    }


}