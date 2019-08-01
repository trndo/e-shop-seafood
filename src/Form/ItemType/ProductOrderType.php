<?php


namespace App\Form\ItemType;


use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Model\ProductModel;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductOrderType extends AbstractItemType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }

}