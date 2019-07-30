<?php

namespace App\Controller\admin;

use App\Entity\OrderInfo;
use App\Entity\Receipt;
use App\Form\OrderInfoType;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            OrderMapper::modelToEntity($orderModel, $order);

            return $this->redirectToRoute('admin_show_order',[
                'id' => $id
            ]);
        }

        return $this->render('admin/order_edit.html.twig',[
            'order' => $order,
            'form' => $form->createView()
        ]);
    }

}