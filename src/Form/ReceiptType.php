<?php


namespace App\Form;

use App\Entity\Category;
use App\Model\ReceiptModel;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceiptType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Названия рецепта'
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
                'attr' => ['class' => 'form-control'],
                'label' => 'Еденица измирения',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.type = :type')
                        ->setParameter('type', 'receipts');
                },
                'label' => 'Категория',
                'placeholder' => 'Выберите категорию'
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control editor'],
                'label' => 'Описание рецепта',
                'required' => false
            ])
            ->add('titlePhoto', FileType::class, [
                'attr' => ['class' => 'form-control-file'],
                'label' => 'Фото Обложки/Видео обложки'
            ]);
        if (!$options['update'])
            $builder->add('photo', FileType::class, [
                'multiple' => true,
                'attr' => ['class' => 'form-control-file'],
                'label' => 'Доп. фотографии',
                'required' => false,
            ]);
            $builder->add('seoTitle', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'label' => 'Сео Тайтл ',
            ])
            ->add('extraHot',CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label' => 'Острый',
                'required' => false,
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
            ])
            ->add('extraAlcohol',CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label' => 'Алкогольный',
                'required' => false,
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
            ])
            ->add('isAbleToChangePrice', CheckboxType::class, [
                'label' => 'Пересчитать сумму',
                'required' => false
            ])
            ->add('percent',NumberType::class, [
                'label' => 'Процент платёжной системы (Процент/100)',
                'attr' => [
                    'min' => 0.001,
                    'max' => 1,
                    'step' => 0.001
                ]
            ])
            ->add('additionalPrice',NumberType::class,[
                'label' => 'Цена тары на единицу товара',
                'attr' => [
                    'min' => 1,
                    'step' => 0.01
                ]
            ])
            ->add('seoDescription', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Сео Дескрипшн',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Сохранить!'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReceiptModel::class,
            'update' => false
        ]);
    }
}

