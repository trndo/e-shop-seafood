<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Entity\OrderInfo;

interface OrderInfoInterface
{
    public function addOrder(OrderInfo $orderInfo);
}