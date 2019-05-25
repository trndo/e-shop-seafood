<?php

namespace App\Form;

use App\Model\AdminModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,[
            'label' => 'Имя',
            'attr' => ['class' => 'form-control']
        ])
        ->add('surname',TextType::class,[
            'label' => 'Фамилия',
            'attr' => ['class' => 'form-control']
        ])
        ->add('email',EmailType::class, [
            'label' => 'Email',
            'attr' => ['class' => 'form-control']
        ])
        ->add('phone',TextType::class,[
            'label' => 'Телефон(не обязтально)',
            'attr' => ['class' => 'form-control'],
            'required' => false
        ])
        ->add('role',ChoiceType::class, [
            'choices' => [
                'Админ' => 'ROLE_ADMIN',
                'Смотрящий' => 'ROLE_ADMIN_VIEWER',
                'Поставщик' => 'ROLE_ADMIN_SUPPLIER'
            ],
            'label' => 'Роль',
            'attr' => ['class' => 'form-control']
        ])
        ->add('save',SubmitType::class,[
            'label' => 'Добавить и отправить приглашение на почту!',
            'attr' => ['class' => 'btn btn-primary btn-in-form']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',AdminModel::class);
    }
}