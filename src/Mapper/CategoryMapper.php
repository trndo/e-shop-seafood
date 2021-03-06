<?php

namespace App\Mapper;

use App\Entity\Category;
use App\Model\CategoryModel;

final class CategoryMapper
{
    public static function entityToModel(Category $entity): CategoryModel
    {
        $model = new CategoryModel();

        $model->setName($entity->getName())
              ->setSeoTitle($entity->getSeoTitle())
              ->setSeoDescription($entity->getSeoDescription())
              ->setType($entity->getType())
              ->setDisplayType($entity->getDisplayType())
              ->setInitialCardText($entity->getInitialCardText())
              ->setSeoText($entity->getSeoText())
              ->setSeoTitleHeader($entity->getTitleSeoHeader());

        return $model;
    }

    public static function modelToEntity(CategoryModel $model, Category $category): Category
    {
         $category->setName($model->getName())
                  ->setSeoTitle($model->getSeoTitle())
                  ->setSeoDescription($model->getSeoDescription())
                  ->setType($model->getType())
                  ->setDisplayType($model->getDisplayType())
                  ->setInitialCardText($model->getInitialCardText())
                  ->setSeoText($model->getSeoText())
                  ->setTitleSeoHeader($model->getSeoTitleHeader());

         return $category;
    }
}