<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Model\OrderModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/cart/makeOrder")
     *
     * @param Request $request
     * @return Response
     */
    public function order(Request $request): Response
    {
        $user = $this->getUser();

        if ($user && $this->isGranted('IS_AUTHENTICATED_FULLY') && $this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            $orderModel = new OrderModel();
        if (!$user && $this->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
            $orderModel = new OrderModel();

        $form = $this->createForm(OrderType::class,$orderModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
//            $orderInfo->addOrder($user);

            return $this->redirectToRoute('home');
        }

        return $this->render('makeOrder.html.twig',[
            'form' => $form->createView()
        ]);
    }
}