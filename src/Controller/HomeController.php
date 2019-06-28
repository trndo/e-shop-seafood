<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\Receipt;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ProductServiceInterface $productService
     * @param ReceiptServiceInterface $receiptService
     * @return Response
     */
    public function home(ProductServiceInterface $productService,ReceiptServiceInterface $receiptService): Response
    {
        $items = array_merge($productService->getProductsForRating(),$receiptService->getReceiptsForRating());

        usort( $items, function ($product, $receipt){
            if($product->getRating() == $receipt->getRating())
                return null;
            return ($product->getRating() < $receipt->getRating()) ? -1 : 1;
        });

        return $this->render('home.html.twig',[
            'items' => $items
        ]);
    }

    /**
     * @Route("/attention", name="attention")
     */
    public function attendUser(): Response
    {
        return $this->render('attention/attention.html.twig');
    }

}