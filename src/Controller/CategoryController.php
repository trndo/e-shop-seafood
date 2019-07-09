<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="category_show")
     *
     * @param Category $category
     * @return Response
     */
    public function category(Category $category): Response
    {
        return $category->getDisplayType() == 'simple'
            ? $this->render('products.html.twig',['items' => $category->getProducts()])
            : $this->render('productsWithSize.html.twig',['items' => $category->getProducts()]);
    }
}