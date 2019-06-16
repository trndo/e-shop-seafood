<?php


namespace App\Service\EntityService\ProductService;


use App\Collection\ProductCollection;
use App\Entity\Product;
use App\Model\ProductModel;

interface ProductServiceInterface
{
    /**
     * @param ProductModel $model
     */
    public function saveProduct(ProductModel $model): void;

    /**
     * @param Product $product
     */
    public function deleteProduct(Product $product): void ;

    /**
     * @param Product $product
     * @param ProductModel $model
     */
    public function updateProduct(Product $product, ProductModel $model): void ;

    /**
     * @return ProductCollection
     */
    public function getProducts(): ProductCollection;

    /**
     * @param int|null $id
     */
    public function activateProduct(?int $id): void;
}