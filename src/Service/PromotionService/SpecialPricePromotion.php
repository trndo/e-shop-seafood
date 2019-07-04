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
    /**
     * @var SpecialProposition
     */
    private $proposition;

    public function __construct(EntityManagerInterface $entityManager,PromotionModel $model,SpecialProposition $proposition)
    {
        $this->entityManager = $entityManager;
        $this->model = $model;
        $this->proposition = $proposition;
    }

    /**
     * Add product promotion
     *
     * @return SpecialProposition
     */
    public function addProductPromotion(): SpecialProposition
    {
        $sProposition = PromotionMapper::specialPriceProductModelToEntity($this->model,$this->proposition);

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
        $sProposition = PromotionMapper::specialPriceReceiptModelToEntity($this->model,$this->proposition);

        $this->entityManager->persist($sProposition);
        $this->entityManager->flush();

        return $sProposition;
    }
}