<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\OrderInfo;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Form\UserInfoUpdateType;
use App\Mapper\UserMapper;
use App\Model\ResetPasswordModel;
use App\Service\EntityService\UserService\UserServiceInterface;
use App\Service\PaymentService\PaymentHandler;
use App\Service\PaymentService\PaymentInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/forgotPassword", name="enterEmail")
     * @param Request $request
     * @param UserServiceInterface $userService
     * @return \Symfony\Component\HttpKernel\Exception\NotFoundHttpException|Response
     */
    public function enterEmail(Request $request, UserServiceInterface $userService)
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
                return $this->createNotFoundException('Пожалуйста, закончите регистрацию регистрацию');
            }
            if (!$user) {
                return $this->createNotFoundException('Такая почта ' . $email . ' не найдена!');
            }
            return $this->redirectToRoute('home');
        }
        return $this->render('enter_email.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/newPassword-{passToken}", name="newPassword")
     * @param Request $request
     * @param UserServiceInterface $userService
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newPassword(Request $request, UserServiceInterface $userService, User $user)
    {
        $newPasswordModel = new ResetPasswordModel();
        $options['forgotPassword'] = true;
        $form = $this->createForm(ResetPasswordType::class, $newPasswordModel, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ResetPasswordModel $email */
            $password = $form->getData()->getPassword();
            $user = $userService->getUserByPassToken($user->getPassToken());
            $userService->addNewPassword($user, $password);

            return $this->redirectToRoute('login');
        }

        return $this->render('forgot_password.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/user-{slug}/resetPassword")
     * @param Request $request
     * @param UserServiceInterface $userService
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPassword(Request $request, UserServiceInterface $userService, User $user)
    {
        $resetPasswordModel = new ResetPasswordModel();
        $options['forgotPassword'] = true;
        $options['oldPassword'] = true;
        $form = $this->createForm(ResetPasswordType::class, $resetPasswordModel, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ResetPasswordModel $data */
            $data = $form->getData();
            $oldPassword = $data->getOldPassword();
            $newPassword = $data->getPassword();
            $userService->resetOldPassword($user, $newPassword, $oldPassword);

            return $this->redirectToRoute('home');
        }

        return $this->render('new_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user-{id}")
     * @param User $user
     * @param Request $request
     * @param UserServiceInterface $service
     * @return Response
     */
    public function updateUser(User $user, Request $request, UserServiceInterface $service): Response
    {
        $user = $this->getUser();
        $userInfoModel = UserMapper::entityToUserModel($user);
        $form = $this->createForm(UserInfoUpdateType::class, $userInfoModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            UserMapper::userModelToEntity($userInfoModel, $user);
            $service->updateUserInfo();
        }

        return $this->render('showUser.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/user-{id}/orders", name="user_orders")
     *
     * @param User $user
     * @return Response
     */
    public function history(User $user): Response
    {
        return $this->render('history.html.twig', [
            'orders' => $user->getOrderInfos()
        ]);
    }

    /**
     * @Route("/orders/payment/{orderUniqueId}", name="pay")
     * @param OrderInfo $order
     * @param PaymentInterface $handler
     * @return Response
     */
    public function pay(OrderInfo $order, PaymentInterface $handler): Response
    {
        $payment = $handler->doPayment($order);
        return $this->render('pay.html.twig', [
            'payment' => $payment
        ]);
    }

    /**
     * @Route("/api/confirm/payment/{orderUniqueId}", name="confirmPay")
     * @param Request $request
     * @param OrderInfo $orderInfo
     * @param PaymentInterface $paymentHandler
     * @return JsonResponse
     */
    public function confirmOrder(Request $request, OrderInfo $orderInfo, PaymentInterface $paymentHandler): JsonResponse
    {
        $res = $request->request->get('data');

        $status = $paymentHandler->confirmPayment($orderInfo, $res);

        if (!$status) {
            return new JsonResponse([
                'status' => $status
            ], 400);
        } else {
            return new JsonResponse([
                'status' => $status
            ], 200);
        }
    }
}