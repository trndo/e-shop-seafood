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
     * @return OrdersCollection
     */
    public function getOrders(): OrdersCollection;

    /**
     * @param int $id
     * @return OrderInfo
     */
    public function getOrder(int $id): OrderInfo;
}