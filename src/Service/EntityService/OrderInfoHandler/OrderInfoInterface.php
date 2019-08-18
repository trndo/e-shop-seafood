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
    public function addOrder(OrderModel $orderModel, Request $request): void ;

    /**
     * Return orders collection
     *
     * @param string $status
     * @return OrdersCollection
     */
    public function getOrders(string $status): OrdersCollection ;

    /**
     * @param int $id
     * @return OrderInfo
     */
    public function getOrder(int $id): ?OrderInfo;

    /**
     * @param OrderModel $model
     * @param OrderInfo $orderInfo
     */
    public function updateOrder(OrderModel $model, OrderInfo $orderInfo): void ;

    /**
     * @param int $id
     */
    public function deleteOrder(int $id): void ;


    /**
     * @param int $id
     * @return float
     */
    public function deleteOrderDetail(int $id): ?float ;

    /**
     * @param int $id
     * @return void
     */
    public function updateOrderInfoStatus(int $id): void ;

    /**
     * @param int $userId
     * @return OrdersCollection
     */
    public function getUserOrders(int $userId): OrdersCollection;

    /**
     * @return array
     */
    public function getCountOfOrders(): array ;
}