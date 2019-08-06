<?php

namespace App\Controller\admin;

use App\Entity\OrderDetail;
use App\Entity\OrderInfo;
use App\Entity\Receipt;
use App\Form\OrderInfoType;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
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

        return $this->render('admin/order.html.twig',[
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

        $form = $this->createForm(OrderInfoType::class,$orderModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $orderInfo->updateOrder($orderModel,$order);

            return $this->redirectToRoute('admin_show_order',[
                'id' => $id
            ]);
        }

        return $this->render('admin/order_edit.html.twig',[
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
        ],200);
    }

    /**
     * @Route("/lipadmin/showOrder/{id}/changeStatus" ,name="changeStatus")
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */
    public function changeStatus(?int $id, OrderInfoInterface $orderInfo): RedirectResponse
    {
        $orderInfo->updateOrderInfoStatus($id);

        return $this->redirectToRoute('admin');
    }

}