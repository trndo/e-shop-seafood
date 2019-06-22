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
                    ->addProduct($model->getProduct())
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
            ->addReceipt($model->getReceipt())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function giftProductModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->addGift($model->getGift())
            ->addProduct($model->getProduct())
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
            ->addProduct($model->getProduct())
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
            ->addReceipt($model->getReceipt())
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

        $proposition->addProduct($model->getProduct())
            ->setGlobalPrice($model->getGlobalPrice())
            ->setQuantity($model->getQuantity())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

    public static function globalReceiptProductModelToEntity(PromotionModel $model): SpecialProposition
    {
        $proposition = new SpecialProposition();

        $proposition->addReceipt($model->getReceipt())
            ->setGlobalPrice($model->getGlobalPrice())
            ->setQuantity($model->getQuantity())
            ->setProductSize($model->getProductSize())
            ->setDescription($model->getDescription())
            ->setStatus(false);

        return $proposition;
    }

}