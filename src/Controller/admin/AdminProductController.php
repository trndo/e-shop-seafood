<?php


namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Mapper\ProductMapper;
use App\Model\ProductModel;
use App\Repository\ProductRepository;
use App\Repository\RepositoryInterface\FinderInterface;
use App\Service\EntityService\ProductService\ProductService;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use App\Service\SearchService\SearcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{
    /**
     * @Route("/lipadmin/products", name="products")
     *
     * @param ProductServiceInterface $productService
     * @param Request $request
     * @return Response
     */
    public function products(ProductServiceInterface $productService, Request $request): Response
    {
        $name = $request->query->get('name',null);
        $category = $request->query->getInt('category',null);
        $categories = $productService->getProductsCategories();
        $products = $productService->getProductsByCriteria($name, $category);

        return $this->render('admin/product/products.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/lipadmin/products/{slug}/show", name="showProduct")
     *
     * @param Product $product
     * @return Response
     */
    public function showProduct(Product $product): Response
    {
        return $this->render('admin/product/show_product.html.twig',[
            'product' => $product
        ]);
    }

    /**
     * @Route(path="/lipadmin/products/byCategory", methods={"GET"})
     *
     * @param Request $request
     * @param ProductServiceInterface $service
     * @return Response
     */
    public function getProductsByCategory(Request $request, ProductServiceInterface $service): Response
    {
        $category = $request->query->get('category');
        $products = $service->getProductsByCriteria(null, $category);
        return $this->render('elements/all_products_for_receipts.html.twig', [
            'products' => $products,
        ]);
    }



}