<?php


namespace App\Service\PaymentService;


use App\Entity\OrderInfo;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WayForPay\SDK\Collection\ProductCollection;
use WayForPay\SDK\Credential\AccountSecretTestCredential;
use WayForPay\SDK\Domain\Client;
use WayForPay\SDK\Domain\Product;
use WayForPay\SDK\Domain\TransactionBase;
use WayForPay\SDK\Exception\WayForPaySDKException;
use WayForPay\SDK\Handler\ServiceUrlHandler;
use WayForPay\SDK\Wizard\PurchaseWizard;

class WayForPayPaymentHandler implements PaymentInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager, UrlGeneratorInterface $generator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->generator = $generator;
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
    public function confirmPayment(OrderInfo $orderInfo): bool
    {
        if ($orderInfo && $orderInfo->getStatus() == 'confirmed') {
            $credential = new AccountSecretTestCredential();
            //$credential = new AccountSecretCredential('account', 'secret');

            try {
                $handler = new ServiceUrlHandler($credential);
                $response = $handler->parseRequestFromPostRaw();
                $status = $response->getTransaction()->getStatus();

                if ($status == TransactionBase::STATUS_APPROVED) {
                    $this->handleConfirmation($orderInfo);
                } else {
                    $orderInfo->setStatus('failed');
                    $this->entityManager->flush();

                    return $this->generator->generate('user_orders', [
                        'uniqueId' => $orderInfo->getUser()->getUniqueId()
                    ]);
                }
            } catch (WayForPaySDKException $e) {
                echo "WayForPay SDK exception: " . $e->getMessage();
            }

            return true;
        }

        return false;
    }

    private function handleConfirmation(OrderInfo $orderInfo): bool
    {

            $orderDetails = $orderInfo->getOrderDetails();
            $user = $orderInfo->getUser();

            foreach ($orderDetails as $orderDetail) {
                $quantity = $orderDetail->getQuantity();
                $productSupply = $orderDetail->getProduct()->getSupply();

                $productSupply->setQuantity($productSupply->getQuantity() - $quantity);
            }

            $orderInfo->setStatus('payed');
//            $user->setBonuses($userBonuses);

            $this->entityManager->flush();

            return true;
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