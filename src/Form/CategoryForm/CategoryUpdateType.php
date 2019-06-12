<?php

namespace App\Form\CategoryForm;

use App\Model\CategoryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('update',CategoryInfoType::class,[
            'data_class' => CategoryModel::class,
            'label' =>  false
        ]);
    }
}