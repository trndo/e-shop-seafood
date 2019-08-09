<?php


namespace App\Service\EntityService\ProductService;


use App\Collection\ProductCollection;
use App\Entity\Category;
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

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return ProductCollection
     */
    public function getProductsByCriteria(array $criteria, array $orderBy = []): ProductCollection;

    /**
     * Get products for rating
     *
     * @return array|null
     */
    public function getProductsForRating(): ?array;

    /**
     * Get product by slug
     *
     * @param string|null $slug
     * @return Product
     */
    public function getProduct(?string $slug): Product ;

    /**
     * @param int|null $id
     * @return Product
     */
    public function getProductById(?int $id): Product;

    /**
     * @param Category $category
     * @return ProductCollection|null
     */
    public function getProductsByCategory(Category $category): ?array ;
}