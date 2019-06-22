<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Product;
use App\Entity\Receipt;
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

class GlobalSpecialPricePromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('globalPrice',IntegerType::class)
            ->add('availableAt',DateType::class)
            ->add('quantity', IntegerType::class);
        if($options['receipt'])
            $builder
                ->add('receipt',EntityType::class,[
                    'class' => Receipt::class,
                    'choice_label' => 'name',
                ]);
        else
            $builder
                ->add('product',EntityType::class,[
                    'class' => Product::class,
                    'choice_label' => 'name',
                ]);
        $builder->add('productSize',TextType::class)
            ->add('description',TextareaType::class)
            ->add('save',SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => PromotionModel::class,
            'receipt' => false
        ]);
    }
}