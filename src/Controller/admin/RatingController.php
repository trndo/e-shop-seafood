<?php

namespace App\Controller\admin;

use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("/lipadmin/rating", name="rating")
     *
     * @param ProductServiceInterface $productService
     * @param ReceiptServiceInterface $receiptService
     * @return Response
     */
    public function rating(ProductServiceInterface $productService,
                           ReceiptServiceInterface $receiptService): Response
    {
        $result = array_merge($productService->getProductsForRating(),$receiptService->getReceiptsForRating());
        usort($result, function ($p,$r){
            if($p->getRating() == $r->getRating())
                return null;
            return ($p->getRating() < $r->getRating()) ? -1 : 1;
        });

        return $this->render('admin/rating/rating.html.twig',[
            'results' => $result
        ]);
    }
}