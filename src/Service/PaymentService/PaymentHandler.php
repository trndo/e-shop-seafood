<?php


namespace App\Service\PaymentService;


use App\Entity\OrderInfo;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentHandler implements PaymentInterface
{
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
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * PaymentHandler constructor.
     * @param OrderInfoInterface $orderInfo
     * @param UrlGeneratorInterface $generator
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(OrderInfoInterface $orderInfo, UrlGeneratorInterface $generator, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->orderInfo = $orderInfo;
        $this->publicKey = getenv('PUBLIC_KEY');
        $this->privateKey = getenv('PRIVATE_KEY');
        $this->generator = $generator;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
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
                'description' => 'Оплата заказа № ' . $orderInfo->getOrderUniqueId(),
                'result_url' => $this->generator->generate(
                    'home', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'language' => 'ru',
                'order_id' => $orderInfo->getOrderUniqueId(),
                'server_url' => $this->generator->generate(
                    'confirmPay', [
                    'orderUniqueId' => $orderInfo->getOrderUniqueId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

            return $form;
        }
    }

    public function confirmPayment(OrderInfo $orderInfo, string $res): bool
    {
        if ($orderInfo && $orderInfo->getStatus() == 'confirmed') {
            $data = json_decode(base64_decode($res, true));

            switch ($data->status) {
                case 'success':
                case 'sandbox':
                    return $this->handleConfirmation($orderInfo, $data) == true ?: null;
                case 'failure':
                case 'error':
                case '3ds_verify':
                case 'wait_secure':
                case 'wait_accept':
                case 'processing':
                    break;
            }
            return true;
        }

        return false;
    }

    private function handleConfirmation(OrderInfo $orderInfo, object $res): bool
    {
        if ($orderInfo->getTotalPrice() == $res->amount) {
            $orderDetails = $orderInfo->getOrderDetails();
            $user = $orderInfo->getUser();
            $userBonuses = $user->getBonuses();

            foreach ($orderDetails as $orderDetail) {
                $receipt = $orderDetail->getReceipt();
                $product = $orderDetail->getProduct();
                $quantity = $orderDetail->getQuantity();

                if ($orderDetail->getReceipt() !== null) {
                    $userBonuses = ($receipt->getPrice() * ceil($quantity) + $product->getPrice() * $quantity) * 0.1 + $userBonuses;
                }
            }

            $orderInfo->setStatus('payed');
            $user->setBonuses($userBonuses);

            $this->entityManager->flush();

            return true;
        }

        return false;
    }

}