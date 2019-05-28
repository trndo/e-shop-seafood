<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Model\UserRegistrationModel;
use App\Security\LoginFormAuthenticator;
use App\Service\EntityService\UserService\UserService;
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
     * @param string $token
     * @param UserService $userService
     * @return Response
     */
    public function confirmRegistration(string $token,UserService $userService,Request $request,
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
}
