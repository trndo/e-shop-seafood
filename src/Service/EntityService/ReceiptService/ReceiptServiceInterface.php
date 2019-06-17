<?php

namespace App\Service\EntityService\ReceiptService;

use App\Collection\ReceiptCollection;
use App\Entity\Receipt;
use App\Model\ReceiptModel;

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
    public function getRecipes(): ReceiptCollection;

    /**
     * @param int|null $id
     */
    public function activateReceipt(?int $id): void;
}