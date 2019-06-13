<?php


namespace App\Controller\admin;


use App\Form\ProductType;
use App\Model\ProductModel;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\FileSystemService\UploadFileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
}