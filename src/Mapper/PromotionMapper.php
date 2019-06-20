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
                    ->setStatus(false);

        return $proposition;
    }
}