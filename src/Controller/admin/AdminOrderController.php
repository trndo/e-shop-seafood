<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /**
     * @Route("/lipadmin/showOrder/" ,name="admin_show_order")
     * @return Response
     */
    public function order(): Response
    {
        return $this->render('admin/order.html.twig');
    }
}