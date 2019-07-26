<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Entity\OrderInfo;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderInfoHandler implements OrderInfoInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * OrderInfoHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addOrder(OrderModel $orderModel, Request $request): void
    {
        $orderInfo = OrderMapper::orderModelToEntity($orderModel);
        $totalSum = $request->getSession()->get('totalSum');

        $orderInfo->setTotalPrice($totalSum);

        $this->entityManager->persist($orderInfo);
        $this->entityManager->flush();

    }
}