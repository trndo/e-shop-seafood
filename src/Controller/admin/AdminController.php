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
     *
     * @param Request $request
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */
    public function homeAdmin(Request $request, OrderInfoInterface $orderInfo): Response
    {
        $status = $request->query->get('status','new');
        $orders = $orderInfo->getOrders($status);
        $statusCount = $orderInfo->getCountOfOrders();

        return $this->render('admin/admin.html.twig',[
            'orders' => $orders,
            'statusCount' => $statusCount
        ]);
    }



}