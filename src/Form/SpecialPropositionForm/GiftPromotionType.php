<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Product;
use App\Model\PromotionModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class GiftPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gift', EntityType::class,[
            'class' => Product::class,
            'choice_label' => 'name'
        ])
            ->add('availableAt',DateType::class)
            ->add('promotion',PromotionInfoType::class,[
                'data_class' => PromotionModel::class,
                'label' => false
            ]);
    }
}