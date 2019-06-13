<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Model\ProductModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('unit',ChoiceType::class,[
                'choices' => [
                    'кг' => 'kg',
                    'грамм' => 'gr',
                    'литр' => 'liter',
                    'шт' => 'thing'
                ]
            ])
            ->add('price',MoneyType::class)
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('description',TextareaType::class)
            ->add('titlePhoto',FileType::class)
            ->add('photo',FileType::class,[
                'multiple' => true
            ])
            ->add('productSize',TextType::class)
            ->add('amountPerUnit',TextType::class)
            ->add('weightPerUnit',TextType::class)
            ->add('seoTitle',TextType::class)
            ->add('seoDescription',TextType::class)
            ->add('create',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductModel::class,
        ]);
    }
}
