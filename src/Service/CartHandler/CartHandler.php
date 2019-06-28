<?php


namespace App\Service\CartHandler;


use App\Repository\ProductRepository;
use App\Repository\ReceiptRepository;

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
}