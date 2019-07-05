<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product")
     *
     * @return Response
     */
    public function productWithSize(): Response
    {
        return  $this->render('product.html.twig');
    }

    /**
     * @Route("/receipt")
     *
     * @return Response
     */
    public function product(): Response
    {
        return  $this->render('receipt.html.twig');
    }
}