<?php


namespace App\Service\EntityService\OrderInfoHandler;


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
}