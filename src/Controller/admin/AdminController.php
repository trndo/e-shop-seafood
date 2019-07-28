<?php

namespace App\Controller\admin;

use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     */
    public function homeAdmin(OrderInfoInterface $orderInfo): Response
    {
        $orders = $orderInfo->getOrders();

        return $this->render('admin/admin.html.twig',[
            'orders' => $orders
        ]);
    }



}