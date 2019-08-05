<?php


namespace App\Form;


use App\Entity\OrderDetail;
use App\Model\OrderModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('orderDate',DateType::class,[
                    'label' => false
                ])
                ->add('orderTime',TimeType::class,[
                    'label' => false
                ])
                ->add('orderDetails',CollectionType::class, [
                    'entry_type' => OrderDetailsType::class,
                    'by_reference' => false,
                    'prototype' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
                ->add('totalPrice',NumberType::class,[
                    'label' => 'Cумма'
                ])
                 ->add('submit', SubmitType::class,[
                     'label' => 'Update'
                 ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => OrderModel::class
        ]);
    }


}