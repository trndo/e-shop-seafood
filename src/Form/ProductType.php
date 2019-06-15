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
            ->add('name',TextType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Названия продукта'
            ])
            ->add('price',MoneyType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Цена'
            ])
            ->add('unit',ChoiceType::class,[
                'choices' => [
                    'кг' => 'кг',
                    'грамм' => 'грамм',
                    'литр' => 'литр',
                    'шт' => 'шт'
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Еденица измирения'
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'label' => 'Категория'
            ])
            ->add('description',TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => ''
            ])
            ->add('titlePhoto',FileType::class,[
                'attr' => ['class' => 'form-control-file'],
                'label' => 'Тайтл фото'
            ])
            ->add('photo',FileType::class,[
                'multiple' => true,
                'attr' => ['class' => 'form-control-file'],
                'label' => 'Доп. фотографии',
                'required' => false
            ])
            ->add('productSize',ChoiceType::class, [
                'attr' => ['class' => 'form-control',],
                'label' => 'Размер продукта(если есть)',
                'choices' => [
                    'S' => 'S',
                    'M' => 'L',
                    'L' => 'L',
                    'XL' => 'XL',
                    'XXL' => 'XXL'
                ],
                'required' => false
            ])
            ->add('amountPerUnit',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Количесвто шт в кг(если есть)'
            ])
            ->add('weightPerUnit',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Вес 1 шт. (если есть)'
            ])
            ->add('seoTitle',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Сео тайтл'
            ])
            ->add('seoDescription',TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Seo Description '
            ])
            ->add('create',SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Сохранить!'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductModel::class,
        ]);
    }
}
