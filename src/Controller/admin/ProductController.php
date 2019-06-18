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
     * @return Response
     */
    public function products(ProductService $productService): Response
    {
        $products = $productService->getProductsByCriteria([],['status' => 'ASC']);

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
        $name = $request->query->get('q');
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
}