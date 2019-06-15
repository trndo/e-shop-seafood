<?php

namespace App\Mapper;

use App\Entity\Product;
use App\Model\ProductModel;

class ProductMapper
{
    static public function entityToModel(Product $product): ProductModel
    {
        $productDto = new ProductModel();
        $productDto->setDescription($product->getDescription())
                ->setName($product->getName())
                ->setPrice($product->getPrice())
                ->setUnit($product->getUnit())
                ->setCategory($product->getCategory())
                ->setProductSize($product->getProductSize())
                ->setSeoTitle($product->getSeoTitle())
                ->setSeoDescription($product->getSeoDescription())
                ->setWeightPerUnit($product->getWeightPerUnit())
                ->setAmountPerUnit($product->getAmountPerUnit());

        return $productDto;
    }
}