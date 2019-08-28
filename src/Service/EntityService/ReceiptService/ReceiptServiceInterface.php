<?php

namespace App\Service\EntityService\ReceiptService;

use App\Collection\CategoryCollection;
use App\Collection\ReceiptCollection;
use App\Entity\Category;
use App\Entity\Receipt;
use App\Model\ReceiptModel;
use Doctrine\Common\Annotations\Annotation\Target;

interface ReceiptServiceInterface
{
    /**
     * @param ReceiptModel $model
     */
    public function saveReceipt(ReceiptModel $model): void;

    /**
     * @param Receipt $receipt
     */
    public function deleteReceipt(Receipt $receipt): void;

    /**
     * @param Receipt $receipt
     * @param ReceiptModel $model
     */
    public function updateReceipt(Receipt $receipt, ReceiptModel $model): void;

    /**
     * @return ReceiptCollection
     */
    public function getReceipts(): ReceiptCollection;

    /**
     * @param int|null $id
     */
    public function activateReceipt(?int $id): void;

    /**
     * @return array|null
     */
    public function getReceiptsForRating(): ?array;

    /**
     * Get receipt
     *
     * @param string|null $slug
     * @return Receipt
     */
    public function getReceipt(?string $slug): Receipt;

    /**
     * @param Category $category
     * @param bool $setMaxResults
     * @return ReceiptCollection|null
     */
    public function getReceiptsByCategory(Category $category, bool $setMaxResults = false): ?ReceiptCollection ;

    /**
     * @param Category $category
     * @param int $count
     * @return ReceiptCollection|null
     */
    public function loadMoreReceipts(Category $category, int $count): ?ReceiptCollection ;

    /**
     * @return CategoryCollection|null
     */
    public function getReceiptsCategories(): ?CategoryCollection ;

    /**
     * @param string $name
     * @param int $category
     * @return ReceiptCollection
     */
    public function getReceiptsByCriteria(?string $name, ?int $category): ?ReceiptCollection ;

    /**
     * @param array $products
     * @param Receipt|null $receipt
     */
    public function addProductsInReceipt(array $products, ?Receipt $receipt): void ;

    /**
     * @param array $products
     * @param Receipt|null $receipt
     */
    public function addSalesInReceipt(array $products, ?Receipt $receipt): void ;

    public function getReceiptById(?int $receiptId): ?Receipt;


}