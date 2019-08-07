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
use App\Repository\ReservationRepository;
use App\Service\CartHandler\CartHandler;
use App\Service\EntityService\ReservationHandler\ReservationInterface;
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
     * @var ReservationInterface
     */
    private $reservation;


    /**
     * OrderInfoHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CartHandler $cartHandler
     * @param OrderDetailRepository $orderDetailRepository
     * @param ReservationInterface $reservation
     */
    public function __construct(EntityManagerInterface $entityManager, CartHandler $cartHandler, OrderDetailRepository $orderDetailRepository,  ReservationInterface $reservation)
    {
        $this->entityManager = $entityManager;
        $this->cartHandler = $cartHandler;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->reservation = $reservation;
    }

    public function addOrder(OrderModel $orderModel, Request $request): void
    {
        $orderInfo = OrderMapper::orderModelToEntity($orderModel);
        $bonuses = $orderInfo->getUser()->getBonuses();
        $session = $request->getSession();
        $totalSum = $session->get('totalSum');
        $reservationId = (int) $session->get('reservationId');
        $items = $this->cartHandler->getItems();

        foreach ($items as $item) {
            $orderDetail = new OrderDetail();
            if ($item['item'] instanceof Receipt) {
                $orderDetail->setReceipt($item['item'])
                    ->setProduct($item['product']);
                $bonuses = ($item['item']->getPrice() * ceil($item['quantity']) + $item['product']->getPrice() * $item['quantity']) * 0.1 + $bonuses;
            }
            if ($item['item'] instanceof Product) {
                $orderDetail->setProduct($item['item']);
            }
            $orderDetail->setQuantity($item['quantity']);
            $orderDetail->setOrderInfo($orderInfo);

            $this->entityManager->persist($orderDetail);
        }

        $orderInfo->setTotalPrice($totalSum);
        $orderInfo->getUser()->setBonuses($bonuses);

        $this->entityManager->persist($orderInfo);

        $this->reservation->deleteReservationsById($reservationId);
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
            $orderDetails = $order->getOrderDetails();
            foreach ($orderDetails as $orderDetail) {
                $product = $orderDetail->getProduct();
                $productSupply = $product->getSupply();
                $productSupply->setReservationQuantity(
                    $productSupply->getReservationQuantity() + $orderDetail->getQuantity()
                );
            }
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

            $supply = $product->getSupply();
            $supply->setReservationQuantity($supply->getReservationQuantity() + $orderDetail->getQuantity());

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

            switch ($orderStatus) {
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

    public function buyItems(int $id): void
    {
        $order = $this->getOrder($id);

        if ($order) {
            $orderDetails = $order->getOrderDetails();
            foreach ($orderDetails as $orderDetail) {
                $orderQuantity = $orderDetail->getQuantity();
                $supply = $orderDetail->getProduct()->getSupply();
                $supplyQuantity = $supply->getQuantity();

                $supply->setQuantity($supplyQuantity - $orderQuantity);
            }
            $this->entityManager->flush();
        }
    }



}