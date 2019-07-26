<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Model\UserRegistrationModel;
use App\Security\LoginFormAuthenticator;
use App\Service\EntityService\UserService\UserService;
use App\Service\EntityService\UserService\UserServiceInterface;
use App\Service\RegistrationService\RegisterUser;
use App\Service\RegistrationService\RegisterUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param RegisterUserInterface $registerService
     * @return Response
     */
    public function register(Request $request,RegisterUserInterface $registerService): Response
    {
        $registrationModel = new UserRegistrationModel();

        $form = $this->createForm(UserRegistrationType::class,$registrationModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $registerService->registerUser($form->getData());

            return $this->redirectToRoute('attention');
        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/confirmation/{token}", name="confirmation")
     *
     * @param string $token
     * @param UserServiceInterface $userService
     * @param Request $request
     * @param GuardAuthenticatorHandler $handler
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @param RegisterUserInterface $registerUser
     * @return Response
     */
    public function confirmRegistration(string $token,UserServiceInterface $userService,Request $request,
                                        GuardAuthenticatorHandler $handler,
                                        LoginFormAuthenticator $loginFormAuthenticator,
                                        RegisterUserInterface $registerUser): Response
    {
        $user = $userService->getUserByToken($token);

        if ($user instanceof User) {

            $registerUser->confirmUser($user);

            return $handler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }
        return new Response('Ooops 404',404);
    }

    /**
     * @Route("/confirmUnknownRegistration/{email}", name="confirmUnknownRegistration")
     * @param User $user
     * @param UserServiceInterface $userService
     * @param Request $request
     * @param GuardAuthenticatorHandler $handler
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @return Response
     */
    public function loginUnknownUser(User $user, UserServiceInterface $userService, Request $request,
                                     GuardAuthenticatorHandler $handler,
                                     LoginFormAuthenticator $loginFormAuthenticator): Response
    {
        $user = $userService->findUserByEmail($user->getEmail());

        if ($user instanceof User) {

            return $handler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }

        return $this->redirectToRoute('home');
    }
}
