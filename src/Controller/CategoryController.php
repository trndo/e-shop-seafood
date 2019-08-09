<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\EntityService\CategoryService\CategoryServiceInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category-{slug}", name="category_show")
     *
     * @param Category $category
     * @param ProductServiceInterface $productService
     * @param ReceiptServiceInterface $receiptService
     * @return Response
     */
    public function category(Category $category, ProductServiceInterface $productService, ReceiptServiceInterface $receiptService): Response
    {
        $items = $category->getType() == 'products' ? $productService->getProductsByCategory($category) : $receiptService->getReceiptsByCategory($category);
        return $category->getDisplayType() == 'simple'
            ? $this->render('products.html.twig',[
                'items' => $items,
                'active' => $category->getSlug()
            ])
            : $this->render('productsWithSize.html.twig',[
                'items' => $items,
                'active' => $category->getSlug()
            ]);
    }

    /**
     * @param CategoryServiceInterface $categoryService
     * @return Response
     */
    public function renderCategories(CategoryServiceInterface $categoryService): Response
    {
        $categories = $categoryService->getCategoryForHeader();

        return $this->render('elements/category_list.html.twig',[
            'categories' => $categories]
        );
    }
}