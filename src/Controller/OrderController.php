<?php

namespace App\Controller;

use App\Entity\Receipt;
use App\Entity\User;
use App\Form\OrderType;
use App\Mapper\OrderMapper;
use App\Model\OrderModel;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use App\Service\EntityService\UserService\UserServiceInterface;
use App\Service\RegistrationService\RegisterUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/cart/makeOrder", name="makeOrder")
     *
     * @param Request $request
     * @param OrderInfoInterface $orderInfo
     * @param UserServiceInterface $userService
     * @param RegisterUserInterface $registerUser
     * @return Response
     */
    public function makeOrder(Request $request, OrderInfoInterface $orderInfo, UserServiceInterface $userService, RegisterUserInterface $registerUser): Response
    {
        if ($request->getSession()->get('cart') !== null ){
            $chooseOrder['chooseOrder'] = $request->getSession()->get('chooseOrder');
            $user = $this->getUser();

            $orderModel = $this->getOrderModel($user);
            $form = $this->createForm(OrderType::class, $orderModel, $chooseOrder);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                if ($user) {
                    $user = $userService->setEmptyPropertiesOfUser($user, $orderModel);
                    $orderModel = $orderModel->setUser($user);
                } elseif (null !== ($user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $orderModel->getEmail()]))) {
                    $orderModel->setUser($user);
                    $request->getSession()->set('unique_id', $user->getUniqueId());
                } else {
                    $user = $registerUser->registerUnknownUser($orderModel);
                    $orderModel = $orderModel->setUser($user);

                    $orderInfo->addOrder($orderModel, $request);

                    return $this->redirectToRoute('confirmUnknownRegistration', [
                        'email' => $user->getEmail()
                    ]);
                }
                $orderInfo->addOrder($orderModel, $request);

                return $this->redirectToRoute('attention_order');
            }

            return $this->render('makeOrder.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->redirectToRoute('home');

    }

    private function getOrderModel(?User $user): OrderModel
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY') && $this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            $orderModel = OrderMapper::entityUserToOrderModel($user);
        else
            $orderModel = new OrderModel();

        return $orderModel;
    }


}
