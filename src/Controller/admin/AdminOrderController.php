<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Entity\OrderDetail;
use App\Entity\OrderInfo;
use App\Entity\Receipt;
use App\Form\OrderInfoType;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use App\Service\EntityService\CategoryService\CategoryServiceInterface;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /**
     * @Route("/lipadmin/showOrder/{id}" ,name="admin_show_order")
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */
    public function order(?int $id, OrderInfoInterface $orderInfo): Response
    {
        $order = $orderInfo->getOrder($id);

        return $this->render('admin/order.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("/lipadmin/showOrder/{id}/edit" ,name="editOrder")
     * @param Request $request
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */

    public function editOrder(Request $request, ?int $id, OrderInfoInterface $orderInfo): Response
    {
        $order = $orderInfo->getOrder($id);
        $orderModel = OrderMapper::entityToModel($order);

        $form = $this->createForm(OrderInfoType::class, $orderModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderInfo->updateOrder($orderModel, $order);

            return $this->redirectToRoute('admin_show_order', [
                'id' => $id
            ]);
        }

        return $this->render('admin/order_edit.html.twig', [
            'order' => $order,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/showOrder/{id}/delete" ,name="deleteOrder")
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */
    public function deleteOrder(?int $id, OrderInfoInterface $orderInfo): Response
    {
        $orderInfo->deleteOrder($id);

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/lipadmin/deleteOrderDetail/{id}" ,name="deleteOrderDetail", methods={"DELETE"})
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return JsonResponse
     */
    public function deleteOrderDetail(?int $id, OrderInfoInterface $orderInfo): JsonResponse
    {
        $totalPrice = $orderInfo->deleteOrderDetail($id);

        return new JsonResponse([
            'status' => true,
            'totalPrice' => $totalPrice
        ], 200);
    }

    /**
     * @Route("/lipadmin/showOrder/{id}/changeStatus" ,name="changeStatus")
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return RedirectResponse
     */
    public function changeStatus(?int $id, OrderInfoInterface $orderInfo): RedirectResponse
    {
        $orderInfo->updateOrderInfoStatus($id);

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/lipadmin/showOrder/{id}/cancelOrder" ,name="cancelOrder")
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */
    public function cancelOrder(?int $id, OrderInfoInterface $orderInfo): Response
    {
        $orderInfo->cancelOrder($id);

        return $this->redirectToRoute('admin');
    }

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