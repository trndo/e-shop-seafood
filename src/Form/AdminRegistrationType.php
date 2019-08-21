<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,[
            'label' => 'Ваше имя',
            'attr' => [
                'class' => 'form-control form-control-user'
            ]
        ])
            ->add('surname',TextType::class,[
                'label' => 'Ваша Фамилия',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('phone',TelType::class,[
                'label' => 'Ваш телефон',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ],
                'required' => false
            ])
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Пароль',
                    'attr' => [
                        'class' => 'form-control form-control-user'
                    ]
                ],
                'second_options' => [
                    'label' => 'Повторите пароль',
                    'attr' => [
                        'class' => 'form-control form-control-user'
                    ]
                ],
                'invalid_message' => 'Пароли не совпадают!'
            ])
            ->add('save',SubmitType::class,[
                'label' => 'Подтвердить регистрацию!',
                'attr' => [
                    'class' => 'btn btn-primary btn-user btn-block'
                ]
            ]);
    }


}