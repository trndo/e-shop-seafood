<?php


namespace App\Controller\admin;


use App\Entity\Receipt;
use App\Mapper\ProductMapper;
use App\Service\EntityService\CategoryService\CategoryService;
use App\Service\EntityService\ReceiptService\ReceiptService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReceiptAdditionalController extends AbstractController
{
    /**
     * @Route("lipadmin/receipts/{slug}/saveProducts", methods={"POST"})
     *
     * @param Request $request
     * @param Receipt $receipt
     * @param ReceiptService $receiptService
     * @return JsonResponse
     */
    public function saveProductsForReceipt(Receipt $receipt,Request $request, ReceiptService $receiptService): JsonResponse
    {
        $receiptService->addProductsInReceipt((array)$request->request->get('products'), $receipt);
        return  new JsonResponse([],200);
    }

    /**
     * @Route("lipadmin/receipts/{slug}/checkProducts", methods={"GET"})
     * @param Receipt $receipt
     * @return JsonResponse
     */
    public function checkIfReceiptHasProducts(Receipt $receipt): JsonResponse
    {
        $products = $receipt->getProducts();
        $data = ProductMapper::fromCollectionToArray($products);

        return new JsonResponse($data,200);
    }

    /**
     * @Route("lipadmin/receipts/{slug}/addSales", name="addReceiptSales")
     *
     * @param CategoryService $categoryService
     * @param Receipt $receipt
     * @return Response
     */
    public function addAdditionalSales(Receipt $receipt, CategoryService $categoryService): Response
    {
        $categories = $categoryService->getAllCategories();
        return $this->render('admin/receipt/additionalSales.html.twig',[
            'additionalProds' => array_merge($receipt->getProductSalesFromReceipt()->toArray(), $receipt->getAdditionalReceipts()->toArray()),
            'categories' => $categories,
            'receipt' => $receipt
        ]);
    }


}