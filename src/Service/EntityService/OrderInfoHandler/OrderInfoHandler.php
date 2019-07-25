<?php


namespace App\Service\EntityService\OrderInfoHandler;


use App\Entity\OrderInfo;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use Symfony\Component\HttpFoundation\Request;

class OrderInfoHandler implements OrderInfoInterface
{

    public function addOrder(OrderModel $orderModel, Request $request): void
    {
        dd($request->getUser());
//        $user = $orderModel->setUser();

        $orderInfo = OrderMapper::orderModelToEntity($orderModel);
    }
}