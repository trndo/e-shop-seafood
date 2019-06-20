<?php


namespace App\Service\PromotionService;


use App\Entity\Product;

use App\Entity\SpecialProposition;
use App\Mapper\PromotionMapper;
use App\Model\PromotionModel;
use Doctrine\ORM\EntityManagerInterface;

class PercentPromotion implements PromotionInterface
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
     * Add product promotion
     *
     * @param PromotionModel $model
     * @return SpecialProposition
     */
    public function addProductPromotion(PromotionModel $model): SpecialProposition
    {
       $sProposition = PromotionMapper::percentProductModelToEntity($model);

       $this->entityManager->persist($sProposition);
       $this->entityManager->flush();

       return $sProposition;

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