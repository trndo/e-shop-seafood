<?php


namespace App\Service\PaymentService;


use App\Entity\OrderInfo;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentHandler implements PaymentInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var OrderInfoInterface
     */
    private $orderInfo;

    private $publicKey;

    private $privateKey;
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * PaymentHandler constructor.
     * @param OrderInfoInterface $orderInfo
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(OrderInfoInterface $orderInfo, UrlGeneratorInterface $generator)
    {
        $this->orderInfo = $orderInfo;
        $this->publicKey = getenv('PUBLIC_KEY');
        $this->privateKey = getenv('PRIVATE_KEY');
        $this->generator = $generator;
    }

    /**
     * @param OrderInfo $orderInfo
     * @return string
     */
    public function doPayment(OrderInfo $orderInfo): string
    {
        if ($orderInfo && $orderInfo->getStatus() == 'confirmed') {

            $liqpay = new \LiqPay($this->publicKey, $this->privateKey);
            $form = $liqpay->cnb_form([
                'version' => 3,
                'action' => 'pay',
                'currency' => 'UAH',
                'amount' => $orderInfo->getTotalPrice(),
                'description' => 'Оплата заказа № '.$orderInfo->getOrderUniqueId(),
                'result_url' => $this->generator->generate('confirmPay',['orderUniqueId' => $orderInfo->getOrderUniqueId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'language' => 'ru',
                'order_id' => $orderInfo->getOrderUniqueId(),
                'server_id' => $this->generator->generate('confirmPay',['orderUniqueId' => $orderInfo->getOrderUniqueId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

            return $form;
        }
    }

    public function confirmPayment(OrderInfo $orderInfo,array $res)
    {
//        $liqpay = new \LiqPay($this->publicKey, $this->privateKey);
//       $res = $liqpay->api("payment/status",[
//                'version' => 3,
//                'order_id' => $orderInfo->getOrderUniqueId()
//            ]);
    $this->logger->info(json_encode($res));

    }

}