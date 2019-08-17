<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Collection\OrdersCollection;
use App\Entity\OrderDetail;
use App\Entity\OrderInfo;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\User;
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
    public function __construct
    (EntityManagerInterface $entityManager,
     CartHandler $cartHandler,
     OrderDetailRepository $orderDetailRepository,
     ReservationInterface $reservation
    )
    {
        $this->entityManager = $entityManager;
        $this->cartHandler = $cartHandler;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->reservation = $reservation;
    }

    public function addOrder(OrderModel $orderModel, Request $request): void
    {
        $orderInfo = OrderMapper::orderModelToEntity($orderModel);
        $session = $request->getSession();
        $totalSum = $session->get('totalSum');
        $reservationId = (int)$session->get('reservationId');
        $items = $this->cartHandler->getItems();

        foreach ($items as $item) {
            $orderDetail = new OrderDetail();
            if ($item['item'] instanceof Receipt) {
                $orderDetail->setReceipt($item['item'])
                    ->setProduct($item['product']);
            }
            if ($item['item'] instanceof Product) {
                $orderDetail->setProduct($item['item']);
            }
            $orderDetail->setQuantity($item['quantity']);
            $orderDetail->setOrderInfo($orderInfo);

            $this->entityManager->persist($orderDetail);
        }

        $orderInfo->setTotalPrice($totalSum);
        $orderInfo->setOrderUniqueId($this->generateHash($orderInfo, 7));

        $this->entityManager->persist($orderInfo);

        $this->reservation->deleteReservationsById($reservationId);
        $this->entityManager->flush();

        $session->remove('reservationId');
        $session->remove('cart');
        $session->remove('totalSum');
        $session->remove('chooseOrder');
        $session->remove('reservation');

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
            $this->deleteOrderWithStatus($order);

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
                    $this->deleteFromSupply($order);
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

    public function getUserOrders(int $userId): OrdersCollection
    {
        return new OrdersCollection(
            $this->entityManager->getRepository(
                OrderInfo::class
            )->getOrdersByUserId($userId)
        );
    }


    private function deleteFromSupply(OrderInfo $orderInfo): void
    {
        $orderDetails = $orderInfo->getOrderDetails();
        foreach ($orderDetails as $orderDetail) {
            $orderQuantity = $orderDetail->getQuantity();
            $supply = $orderDetail->getProduct()->getSupply();
            $supplyQuantity = $supply->getQuantity();
            if ($supplyQuantity >= $orderQuantity)
                $supply->setQuantity($supplyQuantity - $orderQuantity);
            else
                throw new \Exception('Вы хотите подтвердить ' . $orderQuantity . ' едениц товара. На складе доступно - ' . $supplyQuantity . ' !!!');
        }
    }

    private function deleteOrderWithStatus(OrderInfo $order): void
    {
        $orderDetails = $order->getOrderDetails();

        if ($order->getStatus() == self::STATUS_CONFIRMED) {
            foreach ($orderDetails as $orderDetail) {
                $product = $orderDetail->getProduct();
                $productSupply = $product->getSupply();
                $productSupply->setReservationQuantity(
                    $productSupply->getReservationQuantity() + $orderDetail->getQuantity()
                )
                    ->setQuantity($productSupply->getQuantity() + $orderDetail->getQuantity());
            }
        } else {
            foreach ($orderDetails as $orderDetail) {
                $product = $orderDetail->getProduct();
                $productSupply = $product->getSupply();
                $productSupply->setReservationQuantity(
                    $productSupply->getReservationQuantity() + $orderDetail->getQuantity()
                );
            }
        }
    }

    private function generateHash(OrderInfo $orderInfo, $len = null)
    {
        $str = $orderInfo->getId() . (new \DateTime())->getTimestamp();

        $binhash = md5($str, true);
        $numhash = unpack('N2', $binhash);
        $hash = $numhash[1] . $numhash[2];
        if ($len && is_int($len)) {
            $hash = substr($hash, 0, $len);
        }
        return $hash;
    }

}