<?php

namespace App\Controller\admin;

use App\Entity\OrderInfo;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param int|null $id
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */

    public function editOrder(?int $id, OrderInfoInterface $orderInfo): Response
    {
        $order = $orderInfo->getOrder($id);


        return $this->render('admin/order.html.twig',[
            'order' => $order
        ]);
    }
}