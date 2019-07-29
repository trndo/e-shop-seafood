<?php


namespace App\Form;


use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\Receipt;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantity',NumberType::class,[
            'label' => false
        ]);
           if ($options['receipt'])
               $builder->add('product',EntityType::class,[
                  'class' => Receipt::class,
                  'choice_label' => function ($product) {
                   /** @var Receipt $product */
                   return $product->getProducts();
                  }
               ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => OrderDetail::class,
            'receipt' => true
        ]);
    }


}