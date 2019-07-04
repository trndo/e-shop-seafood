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

    /**
     * @Route("/productTest/{slug}", methods={"GET"}, name="testProduct")
     *
     * @param string $slug
     * @param ProductServiceInterface $productService
     * @return Response
     */
    public function showProductTest(string $slug,ProductServiceInterface $productService): Response
    {
        $product = $productService->getProduct($slug);

        return $this->render('product_test.html.twig',[
            'product' => $product
        ]);
    }

    /**
     * @Route("/receiptTest/{slug}", methods={"GET"}, name="testReceipt")
     *
     * @param string $slug
     * @param ReceiptServiceInterface $receiptService
     * @return Response
     */
    public function showReceiptTest(string $slug,ReceiptServiceInterface $receiptService): Response
    {
        $receipt = $receiptService->getReceipt($slug);

        return $this->render('receipt_test.html.twig',[
            'receipt' => $receipt
        ]);
    }

}