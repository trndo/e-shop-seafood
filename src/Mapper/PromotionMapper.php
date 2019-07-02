<?php

namespace App\Mapper;

use App\Entity\SpecialProposition;
use App\Model\PromotionModel;

final class PromotionMapper
{
    public static function percentProductModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setPercent($model->getPercent())
                    ->setAvailableAt($model->getAvailableAt())
                    ->setQuantity($model->getQuantity())
                    ->setProduct($model->getProduct())
                    ->setProductSize($model->getProductSize())
                    ->setDescription($model->getDescription())
                    ->setStatus(false);

        return $proposition;
    }

    public static function percentReceiptModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setPercent($model->getPercent())
            ->setAvailableAt($model->getAvailableAt())
            ->setQuantity($model->getQuantity())
            ->setReceipt($model->getReceipt())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function giftProductModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setGift($model->getGift())
            ->setProduct($model->getProduct())
            ->setQuantity($model->getQuantity())
            ->setAvailableAt($model->getAvailableAt())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function giftReceiptModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setGift($model->getGift())
            ->setReceipt($model->getReceipt())
            ->setQuantity($model->getQuantity())
            ->setAvailableAt($model->getAvailableAt())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function specialPriceProductModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setSpecialPrice($model->getSpecialPrice())
            ->setProduct($model->getProduct())
            ->setQuantity($model->getQuantity())
            ->setAvailableAt($model->getAvailableAt())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function specialPriceReceiptModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setSpecialPrice($model->getSpecialPrice())
            ->setReceipt($model->getReceipt())
            ->setQuantity($model->getQuantity())
            ->setAvailableAt($model->getAvailableAt())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function globalPriceProductModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setProduct($model->getProduct())
            ->setGlobalPrice($model->getGlobalPrice())
            ->setQuantity($model->getQuantity())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function globalPriceReceiptModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->setReceipt($model->getReceipt())
            ->setGlobalPrice($model->getGlobalPrice())
            ->setQuantity($model->getQuantity())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

}