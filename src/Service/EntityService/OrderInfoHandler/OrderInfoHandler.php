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
use App\Service\MailService\MailSenderInterface;
use App\Service\SmsSenderService\SmsSenderInterface;
use App\Traits\OrderMailTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class OrderInfoHandler implements OrderInfoInterface
{
    private const STATUS_NEW = 'new';
    private const STATUS_CONFIRMED = 'confirmed';
    private const STATUS_PAYED = 'payed';
    private const STATUS_DONE = 'done';
    private const STATUS_FAILED = 'failed';
    private const STATUS_CANCELED = 'canceled';
    private const STATUS_CONFIRMED_PAYED = 'confirmed_payed';

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
     * @var MailSenderInterface
     */
    private $mailSenderService;
    /**
     * @var SmsSenderInterface
     */
    private $smsSender;
    /**
     * @var RouterInterface
     */
    private $router;


    /**
     * OrderInfoHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CartHandler $cartHandler
     * @param OrderDetailRepository $orderDetailRepository
     * @param ReservationInterface $reservation
     * @param MailSenderInterface $mailSenderService
     * @param SmsSenderInterface $smsSender
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CartHandler $cartHandler,
        OrderDetailRepository $orderDetailRepository,
        ReservationInterface $reservation,
        MailSenderInterface $mailSenderService,
        SmsSenderInterface $smsSender,
        UrlGeneratorInterface $router
    )
    {
        $this->entityManager = $entityManager;
        $this->cartHandler = $cartHandler;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->reservation = $reservation;
        $this->mailSenderService = $mailSenderService;
        $this->smsSender = $smsSender;
        $this->router = $router;
    }

    public function addOrder(OrderModel $orderModel, Request $request): void
    {
        $orderInfo = OrderMapper::orderModelToEntity($orderModel);
        $session = $request->getSession();
        $totalSum = $session->get('totalSum');
        $chooseOrder = $session->get('chooseOrder');
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

        $orderInfo->setTotalPrice($totalSum)
            ->setOrderUniqueId(
                $this->generateHash($orderInfo, 7)
            )
            ->setComment($orderModel->getComment());

        $this->entityManager->persist($orderInfo);

        $this->entityManager->flush();
        $this->mailSenderService->sendAboutMakingOrder($orderInfo->getUser(), $orderInfo);
        $this->mailSenderService->mailToAdmin('Саша, у тебя новый заказ! Зайди и посмотри!!! Ссылка: '.$this->router->generate('admin_show_order', [
                'id' => $orderInfo->getId()
            ], UrlGeneratorInterface::ABSOLUTE_URL));

        $session->remove('reservationId');
        $session->remove('cart');
        $session->remove('totalSum');
        $session->remove('chooseOrder');
        $session->remove('reservation');

    }

    public function getAdminOrders(string $date, string $status): OrdersCollection
    {
        return new OrdersCollection(
            $this->entityManager->getRepository(OrderInfo::class)
                ->getOrdersForAdmin($date, $status)
        );
    }

    public function getOrders(string $date, string $status): OrdersCollection
    {
        return new OrdersCollection(
            $this->entityManager->getRepository(OrderInfo::class)
                ->getOrders($date, $status)
        );
    }

    public function getCountOfOrders(string $date): array
    {
        return $this->entityManager->getRepository(OrderInfo::class)
            ->getOrderStatusCount($date);
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
            $this->returnProductsFromOrder($order);

            $this->entityManager->remove($order);
            $this->entityManager->flush();
        }
    }

    public function getOrder(?int $id): ?OrderInfo
    {
         return $this->entityManager->getRepository(OrderInfo::class)->getOrderById($id);
    }

    public function cancelOrder(?int $id): void
    {
        $orderInfo = $this->getOrderByUniqueId($id);

        if ($orderInfo) {
            $orderDate = $orderInfo->getOrderDate();

            $this->checkIsCurrentDate($orderDate) ? $this->returnProductsFromOrder($orderInfo) : '';
            $this->returnProductsToSupply($orderInfo);

            $this->mailSenderService
                ->mailToAdmin('Саша, заказ был отменён! Зайди и посмотри!!! Ссылка: '
                .$this->router->generate('admin_show_order', [
                    'id' => $orderInfo->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
            );

            $orderInfo->setStatus('canceled');

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

    public function getAllOrders(): array
    {
        return
            $this->entityManager->getRepository(OrderInfo::class)
            ->getAllOrders();

    }

    public function deleteOrderDetail(?int $id): ?float
    {
        $orderDetail = $this->orderDetailRepository->find($id);

        if ($orderDetail) {
            $orderInfo = $orderDetail->getOrderInfo();
            $totalPrice = $orderInfo->getTotalPrice();
            $receipt = $orderDetail->getReceipt();
            $product = $orderDetail->getProduct();
            $quantity = $orderDetail->getQuantity();
            $productSupply = $product->getSupply();
            $orderDate = $orderInfo->getOrderDate();
            $orderDetailPrice = 0;

            $receipt !== null
                ? $orderDetailPrice = $receipt->getPrice() * ceil($quantity) + $product->getPrice() * $quantity
                : $orderDetailPrice = $product->getPrice() * $quantity;

            $orderInfo->setTotalPrice($totalPrice - $orderDetailPrice);

            if ($this->checkIsCurrentDate($orderDate)) {
                $productSupply->setReservationQuantity(
                    $productSupply->getReservationQuantity() + $quantity
                );
            }

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
            $orderDate = $order->getOrderDate();

            switch ($orderStatus) {
                case self::STATUS_NEW :
                    $this->checkIsCurrentDate($orderDate) ? $this->deleteFromSupplyReservation($order) : '';
                    $order->setStatus(self::STATUS_CONFIRMED);
                    $this->smsSender
                        ->sendSms('Гурман! Твой заказ был подтверждён! Зайди в свой личный кабинет и оплати его! Ссылка: '
                        .$this->router->generate('user_orders',[
                            'uniqueId' => $order->getUser()->getUniqueId()
                        ], UrlGeneratorInterface::ABSOLUTE_URL), $order->getOrderPhone()
                    );
                    break;
                case self::STATUS_PAYED:
                    $order->setStatus(self::STATUS_DONE);
                    $this->smsSender
                        ->sendSms('Гурман, твой заказ уже готов! Совсем скоро ты отведаешь липинских сладостей!',
                            $order->getOrderPhone());
                    break;
                case self::STATUS_FAILED:
                    $this->applyOrder($order);
                    break;
                case self::STATUS_CANCELED:
                    $this->applyOrder($order);
                    break;
                default:
                    $order->setStatus(self::STATUS_NEW);
            }

            $this->entityManager->flush();

            $this->mailSenderService->sendAboutChangingStatus($order->getUser(), $order);
        }

    }

    private function deleteFromSupplyReservation(OrderInfo $orderInfo): void
    {
        $orderDetails = $orderInfo->getOrderDetails();

        foreach ($orderDetails as $orderDetail) {
            $orderQuantity = $orderDetail->getQuantity();
            $supply = $orderDetail->getProduct()->getSupply();
            $supplyReservationQuantity = $supply->getReservationQuantity();
            if ($supplyReservationQuantity >= $orderQuantity)
                $supply->setReservationQuantity($supplyReservationQuantity - $orderQuantity);
            else
                throw new \Exception(
                    'Вы хотите подтвердить - ' . $orderQuantity . ' едениц '
                    .$orderDetail->getProduct()->getName().'. В резерве доступно доступно - '
                    . $supplyReservationQuantity . ' !!!'
                );
        }
    }

    private function returnProductsFromOrder(OrderInfo $order): void
    {
        $orderDetails = $order->getOrderDetails();

        if ($order->getStatus() == self::STATUS_CONFIRMED) {
            foreach ($orderDetails as $orderDetail) {
                $product = $orderDetail->getProduct();
                $productSupply = $product->getSupply();
                $productSupply->setReservationQuantity(
                    $productSupply->getReservationQuantity() + $orderDetail->getQuantity()
                );
            }
        }
    }

    private function returnProductsToSupply(OrderInfo $order): void
    {
        $orderDetails = $order->getOrderDetails();

        $orderDate = $order->getOrderDate();

        if ($this->checkIsCurrentDate($orderDate) && $order->getStatus() == self::STATUS_PAYED) {
            foreach ($orderDetails as $orderDetail) {
                $product = $orderDetail->getProduct();
                $productSupply = $product->getSupply();
                $productSupply->setReservationQuantity(
                    $productSupply->getReservationQuantity() + $orderDetail->getQuantity()
                );
            }
        }

        if ($order->getConfirmedPayment() && $order->getStatus() == self::STATUS_PAYED) {

            foreach ($orderDetails as $orderDetail) {
                $quantity = $orderDetail->getQuantity();
                $productSupply = $orderDetail->getProduct()->getSupply();

                $productSupply->setQuantity($productSupply->getQuantity() + $quantity);
            }

            $order->setConfirmedPayment(false);

            $this->entityManager->flush();
        }

    }

    private function generateHash(OrderInfo $orderInfo, $length = null)
    {
        $str = $orderInfo->getId() . (new \DateTime())->getTimestamp();

        $binhash = md5($str, true);
        $numhash = unpack('N2', $binhash);
        $hash = $numhash[1] . $numhash[2];
        if ($length && is_int($length)) {
            $hash = substr($hash, 0, $length);
        }
        return $hash;
    }

    public function getOrderByUniqueId(?int $uniqueId): ?OrderInfo
    {
        return $this->entityManager->getRepository(OrderInfo::class)->getOrderByUniqueId($uniqueId);
    }

    public function getOrdersForToday(): array
    {
        $date = (new \DateTime())->format('Y-m-d');

        $todayOrders = $this->entityManager->getRepository(OrderInfo::class)->getOrdersForAnotherDay($date, 'payed');
        //dd($todayOrders);
        return $this->countTodayOrders($todayOrders);

    }

    public function confirmOrderPayment(?int $id): bool
    {
        $orderInfo = $this->getOrder($id);

        if ($orderInfo) {
            $orderDetails = $orderInfo->getOrderDetails();
            $user = $orderInfo->getUser();

            foreach ($orderDetails as $orderDetail) {
                $quantity = $orderDetail->getQuantity();
                $productSupply = $orderDetail->getProduct()->getSupply();

                $productSupply->setQuantity($productSupply->getQuantity() - $quantity);
            }

            $orderInfo->setConfirmedPayment(true);
            $this->entityManager->flush();

            return true;
        }

        return false;

    }

    private function countTodayOrders(?array $collection): array
    {
        $result = [];

        /** @var OrderInfo $order */
        foreach ($collection as $order) {
            foreach ($order->getOrderDetails() as $orderDetail) {
                if (!array_key_exists($orderDetail->getProduct()->getId(), $result)) {
                    $result[$orderDetail->getProduct()->getId()] = [
                        'quantity' => $orderDetail->getQuantity(),
                        'name' => $orderDetail->getProduct()->getName(),
                        'unit' => $orderDetail->getProduct()->getUnit()
                    ];
                } else {
                    $result[$orderDetail->getProduct()->getId()]['quantity'] += $orderDetail->getQuantity();
                }

            }
        }

        return $result;
    }


    private function applyOrder(OrderInfo $order): void
    {
        $orderDate = $order->getOrderDate();

        $this->checkIsCurrentDate($orderDate) ? $this->deleteFromSupplyReservation($order) : '';
        $order->setStatus(self::STATUS_CONFIRMED);

        $this->smsSender->sendSms('Привет гурман! Твой заказ был подтверждён! Зайди в свой личный кабинет и оплати его! Ссылка: ' . $this->router->generate('user_orders', [
                'uniqueId' => $order->getUser()->getUniqueId()
            ], UrlGeneratorInterface::ABSOLUTE_URL), $order->getOrderPhone()
        );
    }

    private function checkIsCurrentDate(\DateTimeInterface $orderDate): bool
    {
        $currentDate = new \DateTime('today');

        if ($currentDate->format('Y-m-d') !== $orderDate->format('Y-m-d')) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */

}