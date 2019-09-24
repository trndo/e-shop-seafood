<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\OrderInfo;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Form\UserInfoUpdateType;
use App\Mapper\UserMapper;
use App\Model\ResetPasswordModel;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use App\Service\EntityService\UserService\UserServiceInterface;
use App\Service\PaymentService\PaymentHandler;
use App\Service\PaymentService\PaymentInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends AbstractController
{
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

            return $this->redirectToRoute('home');
        }

        return $this->render('forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/user-{uniqueId}/resetPassword", name="resetPass")
     * @param Request $request
     * @param UserServiceInterface $userService
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPassword(Request $request, UserServiceInterface $userService, User $user)
    {
        $this->checkIsValidUser($user);
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
     * @IsGranted("ROLE_USER")
     * @Route("/user-{uniqueId}" , name="user")
     * @param User $user
     * @param Request $request
     * @param UserServiceInterface $service
     * @return Response
     */
    public function updateUser(User $user, Request $request, UserServiceInterface $service): Response
    {
        $this->checkIsValidUser($user);
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
     * @IsGranted("ROLE_USER")
     * @Route("/user-{uniqueId}/orders", name="user_orders")
     *
     * @param User $user
     * @param OrderInfoInterface $orderInfo
     * @return Response
     */
    public function history(User $user, OrderInfoInterface $orderInfo): Response
    {
        $this->checkIsValidUser($user);

        return $this->render('history.html.twig', [
            'orders' => $orderInfo->getUserOrders($user->getId())
        ]);
    }

    /**
     * @Route("/api/orders/{id}")
     * @param Request $request
     * @param OrderInfo $orderInfo
     * @param OrderInfoInterface $infoService
     * @return JsonResponse
     */
    public function showOrderDetails(Request $request, OrderInfo $orderInfo, OrderInfoInterface $infoService): Response
    {
        $orderDetails = $orderInfo->getOrderDetails();

        return $this->render('showOrderDetails.html.twig', [
           'orderDetails' => $orderDetails,
           'orderInfo' => $orderInfo
        ]);
    }

    private function checkIsValidUser(?User $user): void
    {
        if ($this->getUser() !== $user) {
            throw new HttpException('403');
        }
    }

}