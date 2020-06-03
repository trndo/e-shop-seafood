<?php


namespace App\Service\GoogleAnalyticsService;


use App\Entity\OrderDetail;
use App\Entity\OrderInfo;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class EcommerceTracker
{
    private const GOOGLE_ANALYTICS = '_ga';

    private $analytics;

    public function __construct()
    {
        $this->analytics = new Analytics();
    }

    public function simpleTrack(OrderInfo $orderInfo): void
    {
        dd($this->getClientId());
        $this->analytics->setProtocolVersion('1')
            ->setTrackingId(getenv('GOOGLE_TRACKING_ID'))
            ->setClientId($this->getClientId());

        $this->analytics->setTransactionId($orderInfo->getOrderUniqueId())
        ->setRevenue($orderInfo->getTotalPrice())
            ->setShipping(0.00)
            ->setTax($orderInfo->getTotalPrice() * 0.25)
            ->sendTransaction();

        foreach ($orderInfo->getOrderDetails() as $orderDetail) {
            $this->analytics->setTransactionId(1667)
                ->setItemName(
                    $this->isReceipt($orderDetail) ? $orderDetail->getReceipt()->getName()
                        : $orderDetail->getProduct()->getName()
                )
                ->setItemCode(
                    $this->isReceipt($orderDetail) ? $orderDetail->getReceipt()->getId()
                        : $orderDetail->getProduct()->getId()
                )
                ->setItemCategory($orderDetail->getProduct()
                    ->getCategory()
                    ->getName()
                )
                ->setItemPrice($this->isReceipt($orderDetail)
                    ? $orderDetail->getReceipt()->getPrice() + $orderDetail->getProduct()->getPrice()
                    : $orderDetail->getProduct()->getName())
                ->setItemQuantity($orderDetail->getQuantity())
                ->sendItem();
        }
    }


    private function getClientId(): string
    {
        $ga = $_COOKIE[self::GOOGLE_ANALYTICS];
        $explodedValue = explode('.', $ga);

        return $explodedValue[2] . '.' . $explodedValue[3];
    }

    private function isReceipt(OrderDetail $orderDetail): bool
    {
        return $orderDetail->getReceipt() !== null;
    }

}