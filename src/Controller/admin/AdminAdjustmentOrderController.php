<?php


namespace App\Controller\admin;


use App\Entity\OrderInfo;
use App\Service\EntityService\CategoryService\CategoryServiceInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdjustmentOrderController extends AbstractController
{
    /**
     * @Route("/lipadmin/showOrder/{id}/adjustmentOrder" ,name="adjustmentOrder")
     * @param OrderInfo $orderInfo
     * @param CategoryServiceInterface $categoryService
     * @return Response
     */
    public function adjustmentOrder(OrderInfo $orderInfo, CategoryServiceInterface $categoryService): Response
    {
        $products = $categoryService->getCategoriesByType('product');
        $receipts = $categoryService->getCategoriesByType('receipt');

        return $this->render('admin/order_adjustment.html.twig', [
            'productsCategories' => $products,
            'receiptsCategories' => $receipts,
            'orderInfo' => $orderInfo
        ]);
    }

    /**
     * @Route("/orderAdjustment/showItems" ,name="adjustmentShowItems", methods={"POST"})
     * @param Request $request
     * @param ReceiptServiceInterface $receiptService
     * @param ProductServiceInterface $productService
     * @param CategoryServiceInterface $categoryService
     * @return Response
     */
    public function getItemsForAdjustment(Request $request, ReceiptServiceInterface $receiptService, ProductServiceInterface $productService, CategoryServiceInterface $categoryService): Response
    {

        $category = $categoryService->getCategoryById(
            $request->request->getInt('categoryId')
        );

        $items = $category->getType() === 'products'
            ? $productService->getProductsByCategory($category)
            : $receiptService->getReceiptsByCategory($category);

        return $this->render('admin/adjustments_results.html.twig',[
            'items' => $items
        ]);

    }

    /**
     * @Route("/orderAdjustment/checkProductReservation", name="checkProductReservation", methods={"POST"})
     * @param Request $request
     * @param ProductServiceInterface $productService
     * @param ReceiptServiceInterface $receiptService
     * @return JsonResponse
     */
    public function checkProductReservation(Request $request, ProductServiceInterface $productService, ReceiptServiceInterface $receiptService): JsonResponse
    {
        $product = $productService->getProductById($request->request->getInt('productId'));
        $receipt = $receiptService->getReceiptById($request->request->getInt('receiptId'));
        $orderId = $request->request->getInt('orderId');

        $addingResult = $productService->adjustmentAddingProduct($product, $orderId, $receipt);

        return new JsonResponse($addingResult);

    }

    /**
     * @Route("/orderAdjustment/changeProductQuantity", name="changeProductQuantity", methods={"POST"})
     * @param Request $request
     * @param ProductServiceInterface $productService
     * @param ReceiptServiceInterface $receiptService
     * @return JsonResponse
     */
    public function changeProductQuantity(Request $request, ProductServiceInterface $productService, ReceiptServiceInterface $receiptService): JsonResponse
    {
        $product = $productService->getProductById($request->request->getInt('productId'));
        $receipt = $receiptService->getReceiptById($request->request->getInt('receiptId'));
        $orderId = $request->request->getInt('orderId');
        $value = (float) $request->request->get('value');

        $changeResult = $productService->adjustmentProductQuantity($product, $receipt, $value, $orderId);

        return new JsonResponse($changeResult);
    }
}