<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Model\ResetPasswordModel;
use App\Service\EntityService\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $form = $this->createForm(ResetPasswordType::class,$emailModel,$options);
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
            if(!$user){
                return $this->createNotFoundException('Такая почта '.$email.' не найдена!');
            }
            return $this->redirectToRoute('home');
        }
        return $this->render('enter_email.html.twig',[
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
        $form = $this->createForm(ResetPasswordType::class,$newPasswordModel,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ResetPasswordModel $email */
            $password = $form->getData()->getPassword();
            $user = $userService->getUserByPassToken($user->getPassToken());
            $userService->addNewPassword($user,$password);

            return $this->redirectToRoute('login');
        }

        return $this->render('forgot_password.html.twig',[
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
    public function resetPassword(Request $request,UserServiceInterface $userService, User $user)
    {
        $resetPasswordModel = new ResetPasswordModel();
        $options['forgotPassword'] = true;
        $options['oldPassword'] = true;
        $form = $this->createForm(ResetPasswordType::class,$resetPasswordModel,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ResetPasswordModel $data */
            $data = $form->getData();
            $oldPassword = $data->getOldPassword();
            $newPassword = $data->getPassword();
            $userService->resetOldPassword($user,$newPassword,$oldPassword);

            return $this->redirectToRoute('home');
        }

        return $this->render('new_password.html.twig',[
            'form' => $form->createView()
        ]);

    }
}