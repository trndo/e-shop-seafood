<?php


namespace App\Service\PaymentService;


use App\Entity\OrderInfo;

interface PaymentInterface
{
    /**
     * @param OrderInfo $orderInfo
     * @return string
     */
    public function doPayment(OrderInfo $orderInfo): ?string ;

    /**
     * @param OrderInfo $orderInfo
     * @return bool
     */
    public function confirmPayment(OrderInfo $orderInfo): bool ;
}