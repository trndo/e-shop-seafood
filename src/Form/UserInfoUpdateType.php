<?php


namespace App\Form;


use App\Model\UserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,[
            'label' => false
            ])
            ->add('phone',TextType::class, [
                'label' => false
            ])
            ->add('email',TextType::class, [
                'label' => false
            ])
            ->add('address',TextType::class, [
                'label' => false
            ])
            ->add('coordinates',HiddenType::class, [
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserModel::class
        ]);
    }
}