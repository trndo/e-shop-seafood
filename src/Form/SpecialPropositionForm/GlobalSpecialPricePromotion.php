<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Product;
use App\Entity\Receipt;
use App\Model\PromotionModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class GlobalSpecialPricePromotion extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('globalPrice',IntegerType::class)
            ->add('availableAt',DateType::class)
            ->add('promotion',PromotionInfoType::class,[
                'data_class' => PromotionModel::class,
                'label' => false
            ]);

    }
}