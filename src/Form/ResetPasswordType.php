<?php


namespace App\Form;


use App\Model\ResetPasswordModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['email'])
        $builder->add('email',EmailType::class,[
            'label' => false
        ]);
        if ($options['oldPassword'])
            $builder->add('oldPassword', PasswordType::class, [
                'label' => false
            ]);
        if ($options['forgotPassword'])
            $builder->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли не совпадают!',
                'first_options' => ['label' => false],
                'second_options' => ['label' => false]
            ]);

        $builder->add('save',SubmitType::class,[
                'label' => 'Отправить'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => ResetPasswordModel::class,
           'forgotPassword' => false,
           'email' => false,
           'oldPassword' => false
        ]);
    }


}