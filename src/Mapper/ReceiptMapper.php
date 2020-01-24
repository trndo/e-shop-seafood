<?php


namespace App\Mapper;

use App\Entity\Receipt;
use App\Model\ReceiptModel;

final class ReceiptMapper
{
    public static function entityToModel(Receipt $receipt): ReceiptModel
    {
        $receiptDto = new ReceiptModel();
        $receiptDto->setDescription($receipt->getDescription())
            ->setName($receipt->getName())
            ->setPrice($receipt->getPrice())
            ->setUnit($receipt->getUnit())
            ->setCategory($receipt->getCategory())
            ->setSeoTitle($receipt->getSeoTitle())
            ->setSeoDescription($receipt->getSeoDescription())
            ->setAdditionalPrice($receipt->getAdditionalPrice())
            ->setPercent($receipt->getPercent())
            ->setExtraAlcohol($receipt->getExtraAlcohol())
            ->setExtraHot($receipt->getExtraHot())
            ->setIsNew($receipt->getIsNew());

        return $receiptDto;
    }
}