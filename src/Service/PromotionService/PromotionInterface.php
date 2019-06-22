<?php


namespace App\Service\PromotionService;


use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\SpecialProposition;
use App\Model\PromotionModel;

interface PromotionInterface
{
    /**
     * Add product promotion
     *
     * @return SpecialProposition
     */
    public function addProductPromotion(): SpecialProposition;

    /**
     * Add receipt promotion
     *
     * @return SpecialProposition
     */
    public function addReceiptPromotion(): SpecialProposition;
}