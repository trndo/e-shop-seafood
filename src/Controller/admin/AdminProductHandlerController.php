<?php


namespace App\Controller\admin;


use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Mapper\ProductMapper;
use App\Model\ProductModel;
use App\Service\EntityService\CategoryService\CategoryService;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductHandlerController extends AbstractController
{
    /**
     * @Route("/lipadmin/products/create", name="createProduct")
     * @param Request $request
     * @param ProductServiceInterface $productService
     * @return Response
     */
    public function createProduct(Request $request , ProductServiceInterface $productService): Response
    {
        $productModel = new ProductModel();
        $form = $this->createForm(ProductType::class, $productModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productService->saveProduct($productModel);

            return $this->redirectToRoute('products');
        }

        return $this->render('admin/product/create_product.html.twig',[
            'form' => $form->createView()
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
        $form = $this->createForm(ProductType::class, ProductMapper::entityToModel($product) ,$options);
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
     * @Route(path="/lipadmin/products/getForReceipts", methods={"GET"})
     *
     * @param Request $request
     * @param ProductServiceInterface $service
     * @return Response
     */
    public function getProductsForReceipts(Request $request,ProductServiceInterface $service): Response
    {
        $products = $service->getProductsByCriteria(null,$request->query->get('category'));
        return $this->render('elements/products_for_receipts.html.twig', [
            'products' => $products,
            'type' => $request->query->get('type')
        ]);
    }

    /**
     * @Route("lipadmin/products/{slug}/addSales", name="addProductSales")
     *
     * @param CategoryService $categoryService
     * @param Product $product
     * @return Response
     */
    public function addAdditionalSales(Product $product, CategoryService $categoryService): Response
    {
        $categories = $categoryService->getAllCategories();
        return $this->render('admin/product/additionalSales.html.twig',[
            'additionalProds' => array_merge($product->getProducts()->toArray(), $product->getReceiptSales()->toArray()),
            'categories' => $categories,
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
        $category = $this->getDoctrine()->getRepository(Category::class)->find((int)$category);
        $products = $service->getProductsByCategory($category);
        return $this->render('elements/all_products_for_receipts.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("lipadmin/products/{slug}/saveSales", methods={"POST"})
     *
     * @param Product $product
     * @param Request $request
     * @param ProductServiceInterface $receiptService
     * @return JsonResponse
     */
    public function saveSalesForReceipt(Product $product,Request $request, ProductServiceInterface $receiptService): JsonResponse
    {
        $receiptService->addSalesInProduct((array)$request->request->get('products'), $product);
        return new JsonResponse([],200);
    }
}