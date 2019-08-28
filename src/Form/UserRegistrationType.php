<?php


namespace App\Form;


use App\Model\UserRegistrationModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email',EmailType::class,[
            'label' => false
        ])
            ->add('name',TextType::class,[
                'label' => false
            ])
            ->add('friendUniqueId',TextType::class,[
                'label' => false
            ])
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли не совпадают!',
                'first_options'  => ['label' => false,
                    'attr' => [
                        'placeholder' => 'Пароль'
                    ]
                ],
                'second_options' => ['label' => false,
                    'attr' => [
                        'placeholder' => 'Повторите пароль'
                    ]
                ],
            ])
            ->add('save',SubmitType::class,[
                'label' => 'Зарегистрироватся'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationModel::class
        ]);
    }


}