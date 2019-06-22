<?php


namespace App\Form\CategoryForm;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryInfoType extends AbstractType
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
        ->add('seoTitle',TextType::class,[
            'label' => 'Сео тайтл',
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
        ->add('save',SubmitType::class,[
            'label' => 'Добавить категорию!',
            'attr' => [
                'class' => 'btn btn-primary btn-in-form'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'inherit_data' => true
        ]);
    }
}