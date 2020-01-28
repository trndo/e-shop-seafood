<?php


namespace App\Service\RatingService;


use App\Entity\Product;
use App\Entity\Receipt;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class RatingService implements RatingServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ReceiptServiceInterface
     */
    private $receiptService;
    /**
     * @var ProductServiceInterface
     */
    private $productService;

    /**
     * RatingService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ReceiptServiceInterface $receiptService
     * @param ProductServiceInterface $productService
     */
    public function __construct(EntityManagerInterface $entityManager, ReceiptServiceInterface $receiptService, ProductServiceInterface $productService)
    {
        $this->em = $entityManager;
        $this->receiptService = $receiptService;
        $this->productService = $productService;
    }

    /**
     * @param array $rating
     */
    public function updateRating(array $rating): void
    {
        foreach ($rating as $position => $rate) {
           $rate = explode('_',$rate);
           if(!isset($rate[1]))
               return;
           $repo = $rate[0] == 'product' ?  $this->em->getRepository(Product::class) : $this->em->getRepository(Receipt::class);
           $good = $repo->find($rate[1]);
           $good->setRating($position + 1);
           $this->em->flush();
        }
    }

    /**
     * @param string|null $rate
     */
    public function removeFromRate(?string $rate): void
    {
        if(isset($rate)){
            $rate = explode('_',$rate);
            $repo = $rate[0] == 'product' ?  $this->em->getRepository(Product::class) : $this->em->getRepository(Receipt::class);
            $good = $repo->find($rate[1]);
            $good->setRating(0);
            $this->em->flush();
        }
    }

    public function getItems(): ?array
    {
        $items = array_merge($this->productService->getProductsForRating(), $this->receiptService->getReceiptsForRating());

        usort( $items, function ($product, $receipt) {
            if($product->getRating() == $receipt->getRating())
                return null;
            return ($product->getRating() < $receipt->getRating()) ? -1 : 1;
        });

        return $items;
    }

}