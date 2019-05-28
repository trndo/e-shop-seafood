<?php


namespace App\Form\CategoryForm;


use App\Entity\Category;
use App\Form\CategoryInfoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('create',CategoryInfoType::class,[
            'data_class' => Category::class
        ]);
    }

}