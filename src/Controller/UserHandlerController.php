<?php


namespace App\Controller;


use App\Entity\OrderInfo;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Model\ResetPasswordModel;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use App\Service\EntityService\UserService\UserServiceInterface;
use App\Service\PaymentService\PaymentInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserHandlerController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/user-{user_unique_id}/orders/payment/{orderUniqueId}", name="pay")
     * @ParamConverter("user", options={"mapping" = {"user_unique_id" = "uniqueId" }})
     * @param OrderInfo $order
     * @param PaymentInterface $handler
     * @param User $user
     * @return Response
     */
    public function pay(OrderInfo $order, PaymentInterface $handler, User $user): Response
    {
        $this->checkIsValidUser($user);
        $payment = $handler->doPayment($order);
        $response = $this->render('pay.html.twig', [
            'payment' => $payment
        ]);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

    /**
     * @Route("/forgotPassword", name="enterEmail")
     * @param Request $request
     * @param UserServiceInterface $userService
     * @return \Symfony\Component\HttpKernel\Exception\NotFoundHttpException|Response
     */
    public function enterEmail(Request $request, UserServiceInterface $userService):Response
    {
        $emailModel = new ResetPasswordModel();
        $options['email'] = true;
        $form = $this->createForm(ResetPasswordType::class, $emailModel, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ResetPasswordModel $data */
            $data = $form->getData();
            $email = $data->getEmail();
            $user = $userService->findUserByEmail($email);
            if ($user->getRegistrationStatus()) {
                $userService->resetPassword($user);
            }
            if (!$user->getRegistrationStatus()) {
                throw $this->createNotFoundException('Пожалуйста, закончите регистрацию!');
            }
            if (!$user) {
                throw $this->createNotFoundException('Такая почта ' . $email . ' не найдена!');
            }
            return $this->redirectToRoute('user',[
                'uniqueId' => $user->getUniqueId()
            ]);
        }
        return $this->render('enter_email.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/user-{user_unique_id}/orders/cancel/{orderUniqueId}", name="cancelUserOrder")
     * @ParamConverter("user", options={"mapping" = {"user_unique_id" = "uniqueId" }})
     * @param User $user
     * @param int|null $orderUniqueId
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */
    public function cancelUserOrder(User $user, ?int $orderUniqueId, OrderInfoInterface $orderInfo): Response
    {
        $this->checkIsValidUser($user);
        $orderInfo->cancelOrder($orderUniqueId);

        return $this->redirectToRoute('user_orders',[
            'uniqueId' => $user->getUniqueId()
        ]);
    }

    /**
     * @Route("/api/confirm-payment/{orderUniqueId}", name="confirmPay")
     * @param Request $request
     * @param OrderInfo $orderInfo
     * @param PaymentInterface $paymentHandler
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function confirmPay(Request $request, OrderInfo $orderInfo, PaymentInterface $paymentHandler, LoggerInterface $logger): JsonResponse
    {
        $status = $paymentHandler->confirmPayment($orderInfo);

        if (!$status) {
            return new JsonResponse([
                'status' => $status
            ], 200);
        } else {
            return new JsonResponse([
                'status' => $status
            ], 200);
        }
    }

    /**
     * @Route("/payment-status", name="paymentStatus")
     * @param Request $request
     * @param OrderInfoInterface $orderService
     * @return Response
     */
    public function paymentStatus(Request $request, OrderInfoInterface $orderService): Response
    {
        $orderUniqueId = $request->query->get('order');
        $previousUrl = $request->headers->get('referer');

        /** @var OrderInfo $orderInfo */
        $orderInfo = $orderService->getOrderByUniqueId($orderUniqueId);


        if ($orderInfo->getStatus() == 'failed') {
            return $this->render('attention/failedPayment.html.twig',[
                'user' => $orderInfo->getUser()
            ]);
        } elseif ($orderInfo->getStatus() == 'payed')
            return $this->render('attention/successPayment.html.twig',[
                'user' => $orderInfo->getUser()
            ]);
        else {
            return $this->redirect($previousUrl);
        }
    }


    private function checkIsValidUser(?User $user): void
    {
        if ($this->getUser() !== $user) {
            throw new HttpException('403');
        }
    }

    private function checkIsValidOrder(?OrderInfo $orderInfo, ?User $user): void
    {
        if ($orderInfo && $user) {
            if (!$user->getOrderInfos()->contains($orderInfo->getId()))
            {
                throw new NotFoundHttpException();
            }
        }
    }
}