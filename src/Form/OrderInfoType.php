<?php


namespace App\Form;


use App\Model\OrderModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('orderDate',DateType::class)
                ->add('orderTime',TimeType::class)
                ->add('orderDetails',CollectionType::class, [
                    'label' => false,
                    'entry_type' => OrderDetailsType::class,
                    'by_reference' => false,
                    'prototype' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
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