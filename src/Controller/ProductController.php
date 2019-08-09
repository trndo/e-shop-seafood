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
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
     */
    public function item(Category $category,string $slug, ProductServiceInterface $service, ReceiptServiceInterface $receiptService): Response
    {
        if ($category->getType() == 'products')
            $item = $service->getProduct($slug);
        else
           $item = $receiptService->getReceipt($slug);
        return $item->getType() == 'product'
            ? $this->render('product.html.twig', [
                'item' => $item,
                'active' => $item->getCategory()->getSlug()])
            : $this->render('receipt.html.twig', [
                'item' => $item,
                'active' => $item->getCategory()->getSlug()]);
    }

}