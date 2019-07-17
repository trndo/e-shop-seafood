<?php


namespace App\Form;


use App\Model\OrderModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,[
            'label' => false
        ])
            ->add('surname',TextType::class,[
                'label' => false
            ])
            ->add('email',EmailType::class,[
                'label' => false
            ])
            ->add('phoneNumber',TextType::class,[
                'label' => false
            ])
            ->add('orderDate',TextType::class,[
                'label' => false
            ])
            ->add('orderTime',TextType::class,[
                'label' => false
            ])
            ->add('deliveryType',TextType::class,[
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderModel::class
        ]);
    }


}