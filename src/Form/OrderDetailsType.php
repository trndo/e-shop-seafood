<?php


namespace App\Form;


use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Form\ItemType\ProductOrderType;
use App\Form\ItemType\ReceiptOrderType;
use App\Form\ItemType\SupplyType;
use App\Model\OrderDetailModel;
use App\Model\OrderModel;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class OrderDetailsType extends AbstractType
{
    /**
     * @var ProductServiceInterface
     */
    private $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantity', NumberType::class,[
            'label' => false
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var OrderDetail $orderDetail*/
            $orderDetail = $event->getData();
            $form = $event->getForm();

            if (null !== $orderDetail->getReceipt()) {
                $form->add('receipt',ReceiptOrderType::class, [
                    'label' => 'Рецепт',
                    'disabled' => true
                ])
                    ->add('product',ProductOrderType::class, [
                    'label' => 'Продукт',
                    'disabled' => true
                ]);
            } else {
                $form->add('product',ProductOrderType::class, [
                    'label' => 'Продукт',
                    'disabled' => true
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => OrderDetail::class
        ]);
    }


}