<?php

namespace App\Mapper;

use App\Entity\Product;
use App\Model\ProductModel;
use Doctrine\Common\Collections\Collection;

final class ProductMapper
{
    public static function entityToModel(Product $product): ProductModel
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
                ->setAmountPerUnit($product->getAmountPerUnit())
                ->setPercent($product->getPercent())
                ->setAdditionalPrice($product->getAdditionPrice());

        return $productDto;
    }

    public static function fromCollectionToArray(Collection $collection): array
    {
        if(isset($collection[0])) {
            $data['empty'] = false;
            $data['type'] = $collection[0]->getProductSize() ? 'sizes' : 'one';
            $data['category'] = $collection[0]->getCategory()->getId();

            foreach ($collection as $product) {
                $data['ids'][] = $product->getId();
            }

            return $data;
        }
        else
            return ['empty' => true ];

    }
}