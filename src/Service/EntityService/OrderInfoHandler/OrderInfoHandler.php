<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Collection\OrdersCollection;
use App\Entity\OrderDetail;
use App\Entity\OrderInfo;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use App\Repository\OrderDetailRepository;
use App\Service\CartHandler\CartHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderInfoHandler implements OrderInfoInterface
{
    private const STATUS_NEW = 'new';
    private const STATUS_CONFIRMED = 'confirmed';
    private const STATUS_PAYED = 'payed';
    private const STATUS_DONE = 'done';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CartHandler
     */
    private $cartHandler;
    /**
     * @var OrderDetailRepository
     */
    private $orderDetailRepository;


    /**
     * OrderInfoHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CartHandler $cartHandler
     * @param OrderDetailRepository $orderDetailRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CartHandler $cartHandler, OrderDetailRepository $orderDetailRepository)
    {
        $this->entityManager = $entityManager;
        $this->cartHandler = $cartHandler;
        $this->orderDetailRepository = $orderDetailRepository;
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

    public function getOrder(int $id): ?OrderInfo
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
            $order->getOrderDetails()->
            $this->entityManager->remove($order);
            $this->entityManager->flush();
        }
    }

    public function deleteOrderDetail(int $id): ?float
    {
        $orderDetail = $this->orderDetailRepository->find($id);

        if ($orderDetail) {
            $orderInfo = $orderDetail->getOrderInfo();
            $totalPrice = $orderInfo->getTotalPrice();
            $receipt = $orderDetail->getReceipt();
            $product = $orderDetail->getProduct();
            $quantity = $orderDetail->getQuantity();
            $orderDetailPrice = 0;

            $receipt !== null
                ? $orderDetailPrice = $receipt->getPrice() * ceil($quantity) + $product->getPrice() * $quantity
                : $orderDetailPrice = $product->getPrice() * $quantity;

            $orderInfo->setTotalPrice($totalPrice - $orderDetailPrice);

            $this->entityManager->remove($orderDetail);
            $this->entityManager->flush();

            return $orderInfo->getTotalPrice();
        }
        return null;
    }

    public function updateOrderInfoStatus(int $id): void
    {
        $order = $this->getOrder($id);
        if ($order) {
            $orderStatus = $order->getStatus();

            switch ($orderStatus){
                case self::STATUS_NEW :
                    $order->setStatus(self::STATUS_CONFIRMED);
                    break;
                case self::STATUS_PAYED:
                    $order->setStatus(self::STATUS_DONE);
                    break;
                default:
                    $order->setStatus(self::STATUS_NEW);
            }

            $this->entityManager->flush();
        }

    }


}