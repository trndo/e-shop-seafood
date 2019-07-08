<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Receipt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/product/{slug}", name="product")
     *
     * @param Product $product
     * @return Response
     */
    public function product(Product $product): Response
    {
        return $product->getProductSize()
            ? $this->render('product.html.twig', ['product' => $product])
            : $this->render('receipt.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/receipt/{slug}", name="receipt")
     *
     * @param Receipt $receipt
     * @return Response
     */
    public function receipt(Receipt $receipt): Response
    {
        return $this->render('receipt.html.twig', [
            'product' => $receipt]);
    }
}