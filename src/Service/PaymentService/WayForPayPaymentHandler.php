<?php


namespace App\Service\PaymentService;


use App\Entity\OrderInfo;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WayForPay\SDK\Collection\ProductCollection;
use WayForPay\SDK\Credential\AccountSecretTestCredential;
use WayForPay\SDK\Domain\Client;
use WayForPay\SDK\Domain\Product;
use WayForPay\SDK\Exception\WayForPaySDKException;
use WayForPay\SDK\Handler\ServiceUrlHandler;
use WayForPay\SDK\Wizard\PurchaseWizard;

class WayForPayPaymentHandler implements PaymentInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, LoggerInterface $logger)
    {
        $this->urlGenerator = $urlGenerator;
    }
    /**
     * @inheritDoc
     */
    public function doPayment(OrderInfo $orderInfo): ?string
    {
        if ($orderInfo && ($orderInfo->getStatus() == 'confirmed' || $orderInfo->getStatus() == 'failed')) {
            $credential = new AccountSecretTestCredential();
            //$credential = new AccountSecretCredential('account', 'secret');

            return  $form = PurchaseWizard::get($credential)
                ->setOrderReference($this->generateOrderWayForPayId(7))
                ->setAmount($orderInfo->getTotalPrice())
                ->setCurrency('UAH')
                ->setOrderDate(new \DateTime())
                ->setLanguage('ru')
                ->setMerchantDomainName('https://google.com') //Change to lipinskieraki.com
                ->setClient(new Client(
                    $orderInfo->getUser()->getName() ,
                    $orderInfo->getUser()->getSurname(),
                    $orderInfo->getOrderEmail(),
                    $orderInfo->getOrderPhone(),
                    'Ukraine'
                ))

                ->setProducts(new ProductCollection(
                    $this->getArrayOfOrderItems($orderInfo)
                ))
                ->setReturnUrl($this->urlGenerator->generate(
                    'user_orders', [
                    'uniqueId' =>  $orderInfo->getUser()->getUniqueId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
                )
                ->setServiceUrl($this->urlGenerator->generate(
                    'confirmPay', [
                    'orderUniqueId' => $orderInfo->getOrderUniqueId()
                ], UrlGeneratorInterface::ABSOLUTE_URL))
                ->getForm()
                ->getAsString('Оплатить','btn btn-danger');
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function confirmPayment(OrderInfo $orderInfo, string $res): bool
    {
        if ($orderInfo && $orderInfo->getStatus() == 'confirmed') {
            $credential = new AccountSecretTestCredential();
            //$credential = new AccountSecretCredential('account', 'secret');

            try {
                $handler = new ServiceUrlHandler($credential);
                $response = $handler->parseRequestFromPostRaw();
                $handler->getSuccessResponse($response->getTransaction());
            } catch (WayForPaySDKException $e) {
                echo "WayForPay SDK exception: " . $e->getMessage();
            }
            return true;
        }

        return false;
    }

    private function getArrayOfOrderItems(OrderInfo $orderInfo): array
    {
        $items = [];

        foreach ($orderInfo->getOrderDetails() as $orderDetail) {
            if ($orderDetail->getReceipt() !== null) {
                $receipt = $orderDetail->getReceipt();
                $receiptProduct = $orderDetail->getProduct();
                    $items[] = new Product(
                        $receipt->getName().$receiptProduct->getProductSize() ? ' - '.$receiptProduct->getProductSize() : '',
                        $orderDetail->getQuantity() * $receiptProduct->getPrice() + $receipt->getPrice() * ceil($orderDetail->getQuantity()),
                        $orderDetail->getQuantity()
                    );
            } else {
                $product = $orderDetail->getProduct();
                    $items[] = new Product(
                        $product->getName().' '.$product->getProductSize(),
                       $product->getPrice() * $orderDetail->getQuantity(),
                        $orderDetail->getQuantity()
                    );
            }
        }

        return $items;
    }

    private function generateOrderWayForPayId(int $length): string
    {
        $str = (new \DateTime())->getTimestamp();

        $binHash = md5($str, true);
        $numHash = unpack('N2', $binHash);
        $hash = $numHash[1] . $numHash[2];
        if ($length && is_int($length)) {
            $hash = substr($hash, 0, $length);
        }
        return $hash;
    }
}