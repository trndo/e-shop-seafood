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
     * @param string $res
     * @return bool
     */
    public function confirmPayment(OrderInfo $orderInfo, string $res): bool ;
}