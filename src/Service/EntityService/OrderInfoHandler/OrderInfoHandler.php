<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Collection\OrdersCollection;
use App\Entity\OrderDetail;
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
        $bonuses = $orderInfo->getUser()->getBonuses();

        $totalSum = $request->getSession()->get('totalSum');
        $items = $this->cartHandler->getItems($request);

        foreach ($items as $item) {
            $orderDetails = new OrderDetail();
            if ($item['item'] instanceof Receipt) {
                $orderDetails->setReceipt($item['item'])
                    ->setProduct($item['product']);
                $bonuses = ($item['item']->getPrice() * ceil($item['quantity']) + $item['product']->getPrice() * $item['quantity']) * 0.1 + $bonuses;
            }
            if ($item['item'] instanceof Product) {
                $orderDetails->setProduct($item['item']);
            }
            $orderDetails->setQuantity($item['quantity']);
            $orderDetails->setOrderInfo($orderInfo);

            $this->entityManager->persist($orderDetails);
        }

        $orderInfo->setTotalPrice($totalSum);
        $orderInfo->getUser()->setBonuses($bonuses);

        $this->entityManager->persist($orderInfo);
        $this->entityManager->flush();


    }

    public function getOrders(): OrdersCollection
    {
        return new OrdersCollection($this->entityManager->getRepository(OrderInfo::class)->findBy([], ['id' => 'DESC']));
    }

    public function getOrder(int $id): OrderInfo
    {
        return $this->entityManager->getRepository(OrderInfo::class)->getOrderById($id);
    }

    public function updateOrder(OrderModel $model, OrderInfo $orderInfo): void
    {
        OrderMapper::modelToEntity($model, $orderInfo);
        $this->entityManager->flush();
    }

    public function deleteOrder(int $id): void
    {
        $order = $this->getOrder($id);

        if ($order) {
            $this->entityManager->remove($order);
            $this->entityManager->flush();
        }
    }



}