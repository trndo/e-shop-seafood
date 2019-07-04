<?php


namespace App\Service\EntityService\SpecialPropositionService\Factory;


use App\Entity\SpecialProposition;
use App\Model\PromotionModel;
use App\Service\PromotionService\GiftPromotion;
use App\Service\PromotionService\GlobalSpecialPricePromotion;
use App\Service\PromotionService\PercentPromotion;
use App\Service\PromotionService\PromotionInterface;
use App\Service\PromotionService\SpecialPricePromotion;
use Doctrine\ORM\EntityManagerInterface;

class SpecialPropositionAbstractFactory
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param PromotionModel $model
     * @param SpecialProposition $proposition
     * @return PromotionInterface
     */
    public function createPercentPromotion(PromotionModel $model, SpecialProposition $proposition): PromotionInterface
    {
        return new PercentPromotion($this->entityManager,$model, $proposition);
    }

    /**
     * @param PromotionModel $model
     * @param SpecialProposition $proposition
     * @return PromotionInterface
     */
    public function createSpecialPricePromotion(PromotionModel $model, SpecialProposition $proposition): PromotionInterface
    {
        return new SpecialPricePromotion($this->entityManager,$model,$proposition);
    }

    /**
     * @param PromotionModel $model
     * @param SpecialProposition $proposition
     * @return PromotionInterface
     */
    public function createGlobalSpecialPricePromotion(PromotionModel $model, SpecialProposition $proposition): PromotionInterface
    {
        return new GlobalSpecialPricePromotion($this->entityManager,$model,$proposition);
    }

    /**
     * @param PromotionModel $model
     * @param SpecialProposition $proposition
     * @return PromotionInterface
     */
    public function createGiftPromotion(PromotionModel $model, SpecialProposition $proposition): PromotionInterface
    {
        return new GiftPromotion($this->entityManager,$model,$proposition);
    }
}