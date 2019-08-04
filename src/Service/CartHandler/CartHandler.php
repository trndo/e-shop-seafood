<?php


namespace App\Service\CartHandler;


use App\Entity\Product;
use App\Entity\Receipt;
use App\Repository\ProductRepository;
use App\Repository\ReceiptRepository;
use App\Service\EntityService\ReservationHandler\ReservationInterface;
use App\Service\EntityService\SupplyService\SupplyServiceInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartHandler implements CartHandlerInterface
{
    /**
     * @var ReceiptRepository
     */
    private $receiptRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var SupplyServiceInterface
     */
    private $supplyService;
    /**
     * @var ReservationInterface
     */
    private $reservation;

    /**
     * @var SessionInterface $session
     */
    private $session;

    /**
     * @var bool $todayValidation
     */
    private $todayValidation;

    public function __construct(ReceiptRepository $receiptRepository,
                                ProductRepository $productRepository,
                                ReservationInterface $reservation,
                                SessionInterface $session)
    {
        $this->receiptRepository = $receiptRepository;
        $this->productRepository = $productRepository;
        $this->reservation = $reservation;
        $this->session = $session;
        $this->todayValidation = $session->get('chooseOrder',true);
    }

    /**
     * Add receipt or product to cart
     *
     * @param ParameterBag $requestParams
     * @return array
     */
    public function addItemToCart(ParameterBag $requestParams): array
    {
        $id = $requestParams->get('id');
        $quantity = $requestParams->get('quantity',1);
        $shoppingCart = $this->session->get('cart',[]);

        $item = $this->makeItem($id);
        if($item instanceof Item){
            $item->setQuantity($quantity);
            $this->validateItem($item);
            if($item->isValid()){
                $shoppingCart[$item->getUniqueIndex()] = $item;
                $this->session->set('cart',$shoppingCart);
                $this->countTotalSum();
            }
            return array_merge($item->getResponse(),['totalSum' => $this->session->get('totalSum')]);
        }
        return [ 'status' => true, 'totalSum' => $this->session->get('totalSum')];
    }

    public function removeFromCart(ParameterBag $requestParams): void
    {
        $cart = $this->session->get('cart');
        $key = $requestParams->get('id');

        if (isset($cart[$key])) {
            $this->reservation->deleteReservation($cart[$key]);
            unset($cart[$key]);
            $this->session->set('cart',$cart);
        }
        $this->countTotalSum();
    }

    /**
     * Add item's quantity by key
     *
     * @param ParameterBag $requsetParams
     * @return array
     */
    public function changeItemQuantity(ParameterBag $requsetParams): array
    {
        $key = $requsetParams->get('id');
        $quantity = $requsetParams->get('quantity');
        $cart = $this->session->get('cart',[]);

        if (isset($cart[$key])) {
            /** @var Item $item */
            $item = $cart[$key];
            $item->setQuantity($quantity);
            $this->validateItem($item);
            if($item->isValid()){
                $this->session->set('cart',$cart);
                $this->countTotalSum();
            }
            return array_merge($item->getResponse(),['totalSum' => $this->session->get('totalSum')]);
        }
        return [ 'status' => true, 'totalSum' => $this->session->get('totalSum')];
    }

    private function countTotalSum()
    {
        $total = 0;
        $cart = $this->session->get('cart',[]);

        foreach ($cart as $key => $item) {
            /** @var Item $item */
            if ($item->getItemType() == 'product') {
                $product = $this->productRepository->find($item->getId());
                if ($product instanceof Product) {
                    $total += $product->getPrice() * $item->getQuantity();
                }
            } elseif($item->getItemType() == 'receipt') {
                $receipt = $this->receiptRepository->find($item->getId());
                $product = $this->productRepository->find($item->getRelatedProductId());
                if($product instanceof Product && $receipt instanceof Receipt){
                    $total += $item->getQuantity()*$product->getPrice() + $receipt->getPrice() * ceil($item->getQuantity());
                }
            }
            else
                continue;
        }
        $this->session->set('totalSum', $total);
    }

    private function explodeKey(string $key): array
    {
        return explode('-',$key);

    }

    private function validateItem(Item $item): void
    {
        switch ($item->getItemType()){

            case 'product':
                $product = $this->productRepository->find($item->getId());
                if($product instanceof Product)
                    $this->checkItemQuantityAndReserve($product,$item);
                else $item->setValid(false);
                break;

            case 'receipt':
                $receipt = $this->receiptRepository->find($item->getId());
                $relatedProduct = $this->productRepository->find($item->getRelatedProductId());
                if($receipt instanceof Receipt
                    && $relatedProduct instanceof Product
                    && $receipt->getProducts()->contains($relatedProduct)){
                    $this->checkItemQuantityAndReserve($relatedProduct,$item);
                } else $item->setValid(false);
                break;

            default:
                $item->setValid(false);
        }
    }

    private function makeItem(string $key): ?Item
    {
        if(!preg_match('/^(product|receipt)\-\d+(\-d+)?$/',$key))
            return null;

        $info = $this->explodeKey($key);
        $item = new Item();
        $item->setItemType($info[0])
             ->setId($info[1]);

        if(isset($info[2]) && $item->getItemType() == 'receipt')
            $item->setRelatedProductId((int)$info[2]);

        return  $item;
    }

    /**
     * Return cart items
     *
     * @return array
     */
    public function getItems(): array
    {
        $cart = $this->session->get('cart',[]);
        $result = [];
        foreach ($cart as $key => $item) {
            /** @var Item $item */
            if ($item->getItemType() == 'product') {
                $product = $this->productRepository->find($item->getId());
                if ($product instanceof Product) {
                    $resItem = [
                        'item' => $product,
                        'quantity' => $item->getQuantity()
                    ];
                    $result[] = $resItem;
                }
            } elseif($item->getItemType() == 'receipt') {
                $receipt = $this->receiptRepository->find($item->getId());
                $product = $this->productRepository->find($item->getRelatedProductId());
                if($product instanceof Product && $receipt instanceof Receipt){
                    $resItem = [
                        'item' => $receipt,
                        'product' => $product,
                        'quantity' => $item->getQuantity()
                    ];
                    $result[] = $resItem;
                }
            }
        }
        return $result;
    }

    private function checkItemQuantityAndReserve(Product $product, Item $item): void
    {
        $productQuantity = $product->getSupply()->getQuantity();

        if ($item->getQuantity() > $productQuantity && $this->todayValidation) {
            $item->setValid(false)
                 ->setInvalidMessage('Извините в наличие осталось: '.$productQuantity.' '.$product->getUnit().' продукта')
                 ->setRest($productQuantity);
        }
        else {
            $item->setValid(true);
            $this->reservation->reserve($product,$this->todayValidation,$item->getQuantity());
        }
    }
}