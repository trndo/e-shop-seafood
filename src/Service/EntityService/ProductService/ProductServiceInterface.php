<?php


namespace App\Service\EntityService\ProductService;


use App\Collection\CategoryCollection;
use App\Collection\ProductCollection;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;
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
     * @param string $name
     * @param int $category
     * @return ProductCollection
     */
    public function getProductsByCriteria(?string $name,?int $category): ?ProductCollection ;

    /**
     * @return CategoryCollection|null
     */
    public function getProductsCategories(): ?CategoryCollection ;

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
     * @param bool $setMaxResults
     * @return ProductCollection|null
     */
    public function getProductsByCategory(Category $category, bool $setMaxResults = false): ?array ;

    /**
     * @param Category $category
     * @param int $count
     * @return ProductCollection|null
     */
    public function loadMoreProducts(Category $category, int $count): ?ProductCollection ;

    /**
     * @param Product|null $product
     * @param int|null $orderId
     * @param Receipt|null $receipt
     * @return mixed
     */
    public function adjustmentAddingProduct(?Product $product, ?int $orderId, ?Receipt $receipt): array ;
}