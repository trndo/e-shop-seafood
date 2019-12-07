<?php

namespace App\Controller\admin;

use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller\admin
 *
 */
class AdminController extends AbstractController
{
    /**
     * @Route(path="/lipadmin", name="admin")
     * @param Request $request
     * @param OrderInfoInterface $orderInfo
     * @return Response
     * @throws \Exception
     */
    public function homeAdmin(Request $request, OrderInfoInterface $orderInfo): Response
    {
        $status = $request->query->get('status', 'new');
        $date = $request->query->get('date', (new \DateTime())->format('Y-m-d')).' 00:00:00';
        $orders = $orderInfo->getAdminOrders($date, $status);
        $statusCount = $orderInfo->getCountOfOrders($date);
        $todayOrders = $orderInfo->getOrdersForToday();

        return $this->render('admin/admin.html.twig', [
            'orders' => $orders,
            'statusCount' => $statusCount,
            'date' => $date,
            'todayOrders' => $todayOrders
        ]);
    }

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


}