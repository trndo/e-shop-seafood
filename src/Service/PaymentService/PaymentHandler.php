<?php


namespace App\Service\PaymentService;


use App\Entity\OrderInfo;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentHandler
{
    /**
     * @var OrderInfoInterface
     */
    private $orderInfo;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;


    /**
     * PaymentHandler constructor.
     * @param OrderInfoInterface $orderInfo
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(OrderInfoInterface $orderInfo, ParameterBagInterface $parameterBag)
    {
        $this->orderInfo = $orderInfo;
        $this->parameterBag = $parameterBag;
    }

    public function getFormForPayment(OrderInfo $orderInfo)
    {
        if ($orderInfo && $orderInfo->getStatus() == 'confirmed') {

            $liqpay = new \LiqPay(self::PUBLIC_KEY, self::PRIVATE_KEY);
            $form = $liqpay->cnb_form([
               'version' => 3,
               'action' => 'pay',
               'currency' => 'UAH',
               'amount' => $orderInfo->getTotalPrice(),
               'description' => 'Оплата заказа № '.$orderInfo->getId(),
               'result_url' => 'https://127.0.0.1:8000/',
               'language' => 'ru',
               'order_id' => $orderInfo->getId()
            ]);

            return $form;
        }

    }
}