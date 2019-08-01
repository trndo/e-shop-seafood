<?php


namespace App\Form\ItemType;


use App\Entity\OrderDetail;
use App\Entity\Receipt;
use App\Model\ReceiptModel;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceiptOrderType extends AbstractItemType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Receipt::class
        ]);
    }

}