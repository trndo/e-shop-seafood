<?php


namespace App\Service\EntityService\ProductService;


use App\Entity\Product;
use App\Model\ProductModel;

interface ProductServiceInterface
{
    public function saveProduct(ProductModel $model);
}