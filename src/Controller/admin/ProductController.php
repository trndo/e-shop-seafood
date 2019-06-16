<?php


namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Mapper\ProductMapper;
use App\Model\ProductModel;
use App\Service\EntityService\ProductService\ProductService;
use App\Service\EntityService\ProductService\ProductServiceInterface;
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
     * @Route(path="/lipadmin/products", name="products")
     *
     * @param ProductService $productService
     * @return Response
     */
    public function products(ProductService $productService): Response
    {
        $products = $productService->getProducts();

        return $this->render('admin/product/products.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route(path="/lipadmin/products/{slug}/update", name="updateProduct")
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
     * @Route(path="/lipadmin/products/{slug}/show", name="showProduct")
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
     * @Route(path="/lipadmin/products/{slug}/delete", name="deleteProduct")
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
     * @Route(path="/lipadmin/products/activate", name="activateProduct")
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
}