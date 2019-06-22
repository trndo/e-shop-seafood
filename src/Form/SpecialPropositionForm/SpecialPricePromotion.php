<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Product;
use App\Model\PromotionModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialPricePromotion extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('specialPrice', IntegerType::class)
            ->add('availableAt',DateType::class)
            ->add('promotion', PromotionInfoType::class, [
                'data_class' => PromotionModel::class,
                'label' => false
            ]);
    }
}