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
use function GuzzleHttp\Psr7\parse_header;

class OrderDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantity', NumberType::class, [
            'label' => false
        ]);

        $builder->add('receipt', EntityType::class, [
            'disabled' => true
        ])
            ->add('productReceipt', EntityType::class, [
                'class' => Receipt::class,
                'choice_label' => function ($receipt) {
                    /** @var Receipt $receipt */
                    return $receipt->getProducts();
                }
            ]);

        $builder->add('product', EntityType::class, [
            'disabled' => true
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => OrderDetail::class
        ]);
    }


}