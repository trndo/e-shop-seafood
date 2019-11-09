<?php


namespace App\Service\PaymentService;


use App\Entity\OrderInfo;

class WayForPayPaymentHandler implements PaymentInterface
{

    /**
     * @inheritDoc
     */
    public function doPayment(OrderInfo $orderInfo): ?string
    {
        // TODO: Implement doPayment() method.
    }

    /**
     * @inheritDoc
     */
    public function confirmPayment(OrderInfo $orderInfo, string $res): bool
    {
        // TODO: Implement confirmPayment() method.
    }
}