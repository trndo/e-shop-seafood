<?php


namespace App\Service\PromotionService;


use App\Entity\SpecialProposition;
use App\Mapper\PromotionMapper;
use App\Model\PromotionModel;
use Doctrine\ORM\EntityManagerInterface;

class SpecialPricePromotion implements PromotionInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PromotionModel
     */
    private $model;

    public function __construct(EntityManagerInterface $entityManager,PromotionModel $model)
    {
        $this->entityManager = $entityManager;
        $this->model = $model;
    }

    /**
     * Add product promotion
     *
     * @return SpecialProposition
     */
    public function addProductPromotion(): SpecialProposition
    {
        $sProposition = PromotionMapper::specialPriceProductModelToEntity($this->model);

        $this->entityManager->persist($sProposition);
        $this->entityManager->flush();

        return $sProposition;
    }

    /**
     * Add receipt promotion
     *
     * @return SpecialProposition
     */
    public function addReceiptPromotion(): SpecialProposition
    {
        $sProposition = PromotionMapper::specialPriceReceiptModelToEntity($this->model);

        $this->entityManager->persist($sProposition);
        $this->entityManager->flush();

        return $sProposition;
    }
}