<?php


namespace App\Service\PromotionService;


use App\Entity\SpecialProposition;
use App\Model\PromotionModel;

class SpecialPricePromotion implements PromotionInterface
{

    /**
     * Add product promotion
     *
     * @param PromotionModel $model
     * @return SpecialProposition
     */
    public function addProductPromotion(PromotionModel $model): SpecialProposition
    {
        $specialProposition = new SpecialProposition();
    }

    /**
     * Add receipt promotion
     *
     * @param PromotionModel $model
     * @return SpecialProposition
     */
    public function addReceiptPromotion(PromotionModel $model): SpecialProposition
    {
        $specialProposition = new SpecialProposition();
    }
}