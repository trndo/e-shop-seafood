<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductWithSizeController extends AbstractController
{
    /**
     * @Route("/productsWithSize/{slug}")
     *
     * @param Category $category
     * @return Response
     */
    public function productsWithSize(Category $category): Response
    {
        return $this->render('productsWithSize.html.twig');
    }

    /**
     * @Route("/products")
     * @return Response
     */
    public function products(): Response
    {
        return $this->render('products.html.twig');
    }
}