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

    public function entityToGlobalPriceReceiptModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $model->setReceipt($proposition->getReceipt())
            ->setGlobalPrice($proposition->getGlobalPrice())
            ->setQuantity($proposition->getQuantity())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());

        return $model;
    }

    public function entityToGlobalPriceProductModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $model->setProduct($proposition->getProduct())
            ->setGlobalPrice($proposition->getGlobalPrice())
            ->setQuantity($proposition->getQuantity())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());

        return $model;
    }

    public static function entityToSpecialPriceProductModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $model->setSpecialPrice($proposition->getSpecialPrice())
            ->setProduct($proposition->getProduct())
            ->setQuantity($proposition->getQuantity())
            ->setAvailableAt($proposition->getAvailableAt())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());


        return $model;
    }

    public static function entityToSpecialPriceReceiptModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $model->setSpecialPrice($proposition->getSpecialPrice())
            ->setReceipt($proposition->getReceipt())
            ->setQuantity($proposition->getQuantity())
            ->setAvailableAt($proposition->getAvailableAt())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());


        return $model;
    }

    public static function entityToGiftProductModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $model->setGift($proposition->getGift()->current())
            ->setProduct($proposition->getProduct())
            ->setQuantity($proposition->getQuantity())
            ->setAvailableAt($proposition->getAvailableAt())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());

        return $model;
    }

    public static function entityToGiftReceiptModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $model->setGift($proposition->getGift()->current())
            ->setReceipt($proposition->getReceipt())
            ->setQuantity($proposition->getQuantity())
            ->setAvailableAt($proposition->getAvailableAt())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());

        return $model;
    }

    public static function entityToPercentProductModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $model->setPercent($proposition->getPercent())
            ->setAvailableAt($proposition->getAvailableAt())
            ->setQuantity($proposition->getQuantity())
            ->setProduct($proposition->getProduct())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());

        return $model;
    }

    public static function entityToPercentReceiptModel(SpecialProposition $proposition): PromotionModel
    {
        $model = new PromotionModel();

        $proposition->setPercent($proposition->getPercent())
            ->setAvailableAt($proposition->getAvailableAt())
            ->setQuantity($proposition->getQuantity())
            ->setReceipt($proposition->getReceipt())
            ->setProductSize($proposition->getProductSize())
            ->setDescription($proposition->getDescription());

        return $model;
    }
}