<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/category-{category_slug}/{slug}", name="showItem")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     *
     *
     * @param Category $category
     * @param string $slug
     * @param ProductServiceInterface $service
     * @param ReceiptServiceInterface $receiptService
     * @param SessionInterface $session
     * @return Response
     */
    public function item(Category $category,
                         ?string $slug, ProductServiceInterface $service,
                         ReceiptServiceInterface $receiptService,
                         SessionInterface $session): Response
    {
        if ($category->getType() == 'products')
            $item = $service->getProduct($slug);
        else
           $item = $receiptService->getReceipt($slug);

        $orderType = $session->get('chooseOrder');
        return $item->getType() == 'product'
            ? $this->render('product.html.twig', [
                'item' => $item,
                'active' => $item->getCategory()->getSlug()])
            : $this->render('receipt.html.twig', [
                'item' => $item,
                'sizes' => $orderType ? $item->getProducts()->filter(function (Product $product){
                    return $product->getSupply()->getQuantity() > 0 && $product->getStatus();
                }) : $item->getProducts(),
                'active' => $item->getCategory()->getSlug()]);
    }

    /**
     * @Route("/api/getSizes")
     *
     * @param Request $request
     * @param ProductServiceInterface $productService
     * @return Response
     */
    public function getSizes(Request $request, ProductServiceInterface $productService): Response
    {
        $id = (int)$request->request->get('receipt');
        $orderType = $request->request->get('orderType');
        $sizes = $productService->getSizes($id, $orderType);

        return $this->render('elements/sizes.html.twig', ['products' => $sizes, 'id' => $id]);
    }

}