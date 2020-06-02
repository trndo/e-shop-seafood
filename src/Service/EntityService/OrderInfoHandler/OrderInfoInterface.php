<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Collection\OrdersCollection;
use App\Entity\OrderInfo;
use App\Model\OrderModel;
use Symfony\Component\HttpFoundation\Request;

interface OrderInfoInterface
{
    /**
     * Add order
     *
     * @param OrderModel $orderModel
     * @param Request $request
     * @return void
     */
    public function addOrder(OrderModel $orderModel, Request $request): void;

    /**
     * Return orders collection
     *
     * @param string $date
     * @param string $status
     * @return OrdersCollection
     */
    public function getOrders(string $date, string $status): OrdersCollection;

    /**
     * @param int $id
     * @return OrderInfo
     */
    public function getOrder(?int $id): ?OrderInfo;

    /**
     * @param OrderModel $model
     * @param OrderInfo $orderInfo
     */
    public function updateOrder(OrderModel $model, OrderInfo $orderInfo): void;

    /**
     * @param int $id
     */
    public function deleteOrder(int $id): void;


    /**
     * @param int $id
     * @return float
     */
    public function deleteOrderDetail(?int $id): ?float;

    /**
     * @param int $id
     * @return void
     */
    public function updateOrderInfoStatus(int $id): void;

    /**
     * @param int $userId
     * @return OrdersCollection
     */
    public function getUserOrders(int $userId): OrdersCollection;

    /**
     * @param string $date
     * @return array
     */
    public function getCountOfOrders(string $date): array;

    /**
     * @param int|null $id
     */
    public function cancelOrder(?int $id): void;

    /**
     * @param int|null $uniqueId
     * @return OrderInfo
     */
    public function getOrderByUniqueId(?int $uniqueId): ?OrderInfo ;

    /**
     * @return array
     */
    public function getOrdersForToday(): array ;

    /**
     * @param int|null $id
     * @return bool
     */
    public function confirmOrderPayment(?int $id): bool ;

    /**
     * @param string $date
     * @param string $status
     * @return OrdersCollection
     */
    public function getAdminOrders(string $date, string $status): OrdersCollection;

    /**
     * @return OrdersCollection
     */
    public function getAllOrders(): array ;
}