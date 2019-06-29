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
use App\Service\SearchService\SearcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/lipadmin/products/create", name="createProduct")
     * @param Request $request
     * @param ProductServiceInterface $productService
     * @return Response
     */
    public function createProduct(Request $request ,ProductServiceInterface $productService): Response
    {
        $productModel = new ProductModel();
        $form = $this->createForm(ProductType::class,$productModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productService->saveProduct($productModel);

            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/product/create_product.html.twig',[
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/products", name="products")
     *
     * @param ProductService $productService
     * @param Request $request
     * @return Response
     */
    public function products(ProductService $productService, Request $request): Response
    {
        $products = $productService->getProductsByCriteria($request->query->all(),['status' => 'ASC']);

        return $this->render('admin/product/products.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/lipadmin/products/{slug}/update", name="updateProduct")
     *
     * @param Product $product
     * @param Request $request
     *
     * @param ProductServiceInterface $productService
     * @return Response
     */
    public function updateProduct(Product $product,Request $request, ProductServiceInterface $productService): Response
    {
        $options['update'] = true;
        $form = $this->createForm(ProductType::class, ProductMapper::entityToModel($product),$options);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $productService->updateProduct($product,$form->getData());
            return $this->redirectToRoute('products');
        }

        return $this->render('admin/product/update_product.html.twig', [
            'form' => $form->createView(),
            'product' => $product
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
     * @Route("/lipadmin/products/{slug}/delete", name="deleteProduct")
     *
     * @param Product $product
     *
     * @param ProductServiceInterface $productService
     * @return RedirectResponse
     */
    public function deleteProduct(Product $product, ProductServiceInterface $productService): RedirectResponse
    {
        $productService->deleteProduct($product);

        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/lipadmin/products/activate", name="activateProduct")
     *
     * @param Request $request
     * @param ProductServiceInterface $productService
     * @return JsonResponse
     */
    public function activeProduct(Request $request, ProductServiceInterface $productService): JsonResponse
    {
        $productService->activateProduct($request->request->get('id'));
        return new JsonResponse(['status' => true],200);
    }

    /**
     * @Route("/lipadmin/products/search", name="searchProducts", methods={"GET"})
     *
     * @param Request $request
     * @param SearcherInterface $searcher
     * @param ProductRepository $repository
     * @return JsonResponse
     */
    public function searchProduct(Request $request, SearcherInterface $searcher, ProductRepository $repository): JsonResponse
    {
        $name = $request->query->get('term');
        $products = $searcher->searchByName($name, $repository);

        return new JsonResponse(
            $products
        );
    }

    /**
     * @Route("/lipadmin/products/{slug}/showPhotos", name="showProductPhotos")
     *
     * @param Product $product
     * @return Response
     */
    public function showProductPhotos(Product $product): Response
    {
        return $this->render('admin/product/show_product_photos.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route(path="/lipadmin/products/getForReceipts", methods={"GET"})
     *
     * @param Request $request
     * @param ProductServiceInterface $service
     * @return Response
     */
    public function getProductsForReceipts(Request $request,ProductServiceInterface $service): Response
    {
       $products = $service->getProductsByCriteria(['category' => $request->query->get('category')]);
       return $this->render('elements/products_for_receipts.html.twig', [
           'products' => $products,
           'type' => $request->query->get('type')
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
        $products = $service->getProductsByCriteria(['category' => $request->query->get('category')]);
        return $this->render('elements/all_products_for_receipts.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/lipadmin/products/live_search", methods={"GET"})
     *
     * @param Request $request
     * @param SearcherInterface $searcher
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function liveSearchReceipt(Request $request, SearcherInterface $searcher, ProductRepository $productRepository): Response
    {
        $name = $request->query->get('q');
        $receipts = $searcher->searchByNameForRender($name,$productRepository);

        return $this->render('elements/productsForRating.html.twig',[
            'goods' => $receipts,
            'type' => 'product'
        ]);
    }

}