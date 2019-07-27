<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Entity\OrderDetails;
use App\Entity\OrderInfo;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use App\Service\CartHandler\CartHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderInfoHandler implements OrderInfoInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CartHandler
     */
    private $cartHandler;


    /**
     * OrderInfoHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CartHandler $cartHandler
     */
    public function __construct(EntityManagerInterface $entityManager, CartHandler $cartHandler)
    {
        $this->entityManager = $entityManager;
        $this->cartHandler = $cartHandler;
    }

    public function addOrder(OrderModel $orderModel, Request $request): void
    {
        $orderInfo = OrderMapper::orderModelToEntity($orderModel);

        $totalSum = $request->getSession()->get('totalSum');
        $items = $this->cartHandler->getItems($request);

        foreach ($items as $item) {
            $orderDetails = new OrderDetails();
            if ($item['item'] instanceof Receipt) {
                $orderDetails->setReceipt($item['item'])
                    ->setProduct($item['product']);
            }
            if ($item['item'] instanceof Product) {
                $orderDetails->setProduct($item['item']);
            }
            $orderDetails->setQuantity($item['quantity']);
            $orderDetails->setOrderInfo($orderInfo);

            $this->entityManager->persist($orderDetails);
        }

        $orderInfo->setTotalPrice($totalSum);

        $this->entityManager->persist($orderInfo);
        $this->entityManager->flush();

        dd($orderInfo);


    }
}