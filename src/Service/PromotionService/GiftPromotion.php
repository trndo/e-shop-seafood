<?php


namespace App\Service\PromotionService;


use App\Entity\Category;
use App\Entity\SpecialProposition;
use App\Mapper\PromotionMapper;
use App\Model\PromotionModel;
use Doctrine\ORM\EntityManagerInterface;

class GiftPromotion implements PromotionInterface
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

    public function __construct(EntityManagerInterface $entityManager, PromotionModel $model, SpecialProposition $proposition)
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
        $sProposition = PromotionMapper::giftProductModelToEntity($this->model, $this->proposition);

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
        $sProposition = PromotionMapper::giftReceiptModelToEntity($this->model, $this->proposition);

        $this->entityManager->persist($sProposition);
        $this->entityManager->flush();

        return $sProposition;
    }
}