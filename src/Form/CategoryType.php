<?php


namespace App\Form;


use App\Model\CategoryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,[
            'label' => 'Назваие категории',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('type',ChoiceType::class,[
            'choices' => [
                'Категория для продуктов' => 'products',
                'Категория для рецептов' => 'receipts'
            ],
            'expanded' => true,
            'label' => 'Тип категории'
        ])
        ->add('displayType',ChoiceType::class,[
                'choices' => [
                    'Обычное отображение' => 'simple',
                    'Отображение размерами' => 'size'
                ],
                'expanded' => true,
                'label' => 'Тип отображение категории'
        ])
            ->add('initialCardText',TextType::class,[
                'label' => 'Надпись на начальной карточке',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
        ->add('seoTitle',TextType::class,[
            'label' => 'Сео тайтл',
            'attr' => [
                'class' => 'form-control'
            ],
            'required' => false
        ])
        ->add('seoTitleHeader', TextType::class, [
            'label' => 'Seo h1',
            'attr' => [
                'class' => 'form-control'
            ],
            'required' => false
        ])
        ->add('seoDescription',TextareaType::class,[
            'label' => 'Сео дескрипшн',
            'attr' => [
                'class' => 'form-control'
            ],
            'required' => false
        ])
        ->add('seoText',TextareaType::class,[
            'label' => 'Сео текст',
            'attr' => [
                'class' => 'form-control'
            ],
            'required' => false
        ])
        ->add('titlePhoto',FileType::class,[
            'label' => 'Фото категории',
            'attr' => [
                'class' => 'form-control'
            ],
            'required' => false
        ])
        ->add('save',SubmitType::class,[
            'label' => 'Сохранить!',
            'attr' => [
                'class' => 'btn btn-primary btn-in-form'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryModel::class
        ]);
    }
}