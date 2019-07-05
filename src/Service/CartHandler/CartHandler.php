<?php


namespace App\Service\CartHandler;


use App\Repository\ProductRepository;
use App\Repository\ReceiptRepository;
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

    public function __construct(ReceiptRepository $receiptRepository, ProductRepository $productRepository)
    {
        $this->receiptRepository = $receiptRepository;
        $this->productRepository = $productRepository;
    }


    /**
     * @param string $type
     * @param string $slug
     * @return \App\Entity\Product|\App\Entity\Receipt
     */
    public function getItem(string $type, string $slug)
    {
        if ($type == 'product') {
            return $this->productRepository->findProductBySlug($slug);
        }

        if ($type == 'receipt') {
            return $this->receiptRepository->findReceiptBySlug($slug);
        }
    }

    /**
     * Add receipt or product to cart
     *
     * @param Request $request
     * @param string $key
     * @param array $options
     */
    public function addItemToCart(Request $request,string $key, array $options): void
    {
        $session = $request->getSession();
        $shoppingCart = [];

        if (!$session) {
            $session->set('cart',$shoppingCart);
        } else {
            $shoppingCart = $session->get('cart');
            $shoppingCart[$key] = $options;
            $session->set('cart',$shoppingCart);
        }
    }

    public function removeFromCart(Request $request)
    {
        // TODO: Implement removeFromCart() method.
    }
}