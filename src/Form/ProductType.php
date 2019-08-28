<?php

namespace App\Form;

use App\Collection\ProductCollection;
use App\Entity\Category;
use App\Entity\Product;
use App\Model\ProductModel;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Названия продукта'
            ])
            ->add('price', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'min' => 0],
                'label' => 'Цена',
            ])
            ->add('unit', ChoiceType::class, [
                'choices' => [
                    'кг' => 'кг',
                    'грамм' => 'грамм',
                    'литр' => 'литр',
                    'шт' => 'шт'
                ],
                'placeholder' => 'Единицы измерения',
                'attr' => ['class' => 'form-control'],
                'label' => 'Еденица измирения',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => function ($category) {
                    /**@var Category $category */
                    return $category->getName() . ' - ' . ($category->getDisplayType() == 'size' ? 'S,M,L,XL,XXL' : 'Обычное отображение');
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.type = :type')
                        ->setParameter('type', 'products');
                },
                'attr' => ['class' => 'form-control'],
                'label' => 'Категория',
                'placeholder' => 'Выберите категорию'
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Описание продукта'
            ])
            ->add('titlePhoto', FileType::class, [
                'attr' => ['class' => 'form-control-file'],
                'label' => 'Фото Обложки'
            ]);
        if (!$options['update'])
            $builder->add('photo', FileType::class, [
                'multiple' => true,
                'attr' => ['class' => 'form-control-file'],
                'label' => 'Дополнитильные фотографии',
                'required' => false,
            ]);
        $builder->add('productSize', ChoiceType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Размер продукта(если есть)',
            'choices' => [
                'S' => 'S',
                'M' => 'M',
                'L' => 'L',
                'XL' => 'XL',
                'XXL' => 'XXL'
            ],
            'required' => false,
            'placeholder' => 'Выбирете размер(без размера)'
        ])
            ->add('percent',NumberType::class,[
                'label' => 'Процент платёжной системы (Процент/100)',
                'attr' => [
                    'min' => 0.001,
                    'max' => 1,
                    'step' => 0.001
                ],

            ])
            ->add('additionalPrice',NumberType::class,[
                'label' => 'Цена тары на единицу товара',
                'attr' => [
                    'min' => 1,
                    'step' => 0.01
                ],
                'required' => false

            ])
            ->add('amountPerUnit', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Количесвто шт/кг (не обязательно)',
                'required' => false
            ])
            ->add('weightPerUnit', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Вес за 1 шт. (не обязательно)',
                'required' => false
            ])
            ->add('seoTitle', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'label' => 'Сео тайтл',
            ])
            ->add('seoDescription', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Сео дескрипшн',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Сохранить!'
            ]);


        $productSize = $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            [$this, 'onPostSetData']
        );

        $primarySize = $productSize->getData()->getProductSize();

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($primarySize) {
                /** @var Product $product */
                $product = $event->getForm()->getData();
                $chosenCategory = $product->getCategory();
                $chosenSize = $product->getProductSize();
                $options = $event->getForm()->getConfig()->getOptions();

                if ($chosenCategory->getDisplayType() == 'size' && $chosenSize == null) {
                    $error = new FormError('Вы выбрали категорию для продуктов - с размером(S,M,L..) ! Выберите другую категорию или выберите размер продукта!');
                    $event->getForm()->get('category')->addError($error);
                }

                if ($chosenCategory->getDisplayType() == 'size' && $chosenSize !== null) {
                    $errorSize = $this->findExistedSize($chosenSize, $chosenCategory, $options,$primarySize);
                    if ($errorSize !== null) {
                        $event->getForm()->get('productSize')->addError($errorSize);
                    }
                }
            });

    }

    public function onPostSetData(FormEvent $event)
    {
        /** @var Product $product */
        return  $event->getData();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductModel::class,
            'update' => false
        ]);
    }

    private function findExistedSize(string $chosenSize, Category $chosenCategory, array $options, string $primarySize): ?FormError
    {
        $productsFromCategory = $this->productRepository->findProductsBy(null, $chosenCategory->getId());
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $categorySizes = [];

        foreach ($productsFromCategory as $categoryProduct) {
            /** @var Product $categoryProduct */
            $categorySizes[] = $categoryProduct->getProductSize();
        }

        if (in_array($chosenSize, $categorySizes)) {
            if ($options['update']) {
                if ($primarySize !== $chosenSize){
                    return new FormError('Продукт с размерностью - ' . $chosenSize . ' уже существует в категории - ' . $chosenCategory->getName() . '! 
                    Доступные размеры: ' . implode(',', array_diff($sizes, $categorySizes)));
                }
            } else {
                return new FormError(
                    'Продукт с размерностью - ' . $chosenSize . ' уже существует в категории - ' . $chosenCategory->getName() . '! 
                    Доступные размеры: ' . implode(',', array_diff($sizes, $categorySizes))
                );
            }
        }
        return null;
    }
}