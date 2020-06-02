<?php


namespace App\Traits;


use App\Entity\OrderDetail;
use App\Entity\OrderInfo;
use App\Service\GoogleAnalyticsService\EcommerceTracker;

trait GoogleAnalyticsTrait
{
    /**
     * @var EcommerceTracker
     */
    private $ecommerceTracker;

    /**
     * GoogleAnalyticsTrait constructor.
     * @param EcommerceTracker $ecommerceTracker
     */
    public function __construct(EcommerceTracker $ecommerceTracker)
    {
        $this->ecommerceTracker = $ecommerceTracker;
    }

    public function trackOrder(OrderInfo $orderInfo): void
    {
        $this->ecommerceTracker->simpleTrack($orderInfo);
    }
}