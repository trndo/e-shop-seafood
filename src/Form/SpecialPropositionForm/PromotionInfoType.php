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

class PromotionInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'inherit_data' => true
        ]);
    }
}