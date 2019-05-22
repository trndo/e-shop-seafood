<?php

namespace App\Controller;

use App\Form\UserRegistrationType;
use App\Model\UserRegistrationModel;
use App\Security\LoginFormAuthenticator;
use App\Service\Registration\RegisterUserInterface;
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
    public function register(Request $request,RegisterUserInterface $registerService, GuardAuthenticatorHandler $handler, LoginFormAuthenticator $loginFormAuthenticator): Response
    {
        $registrationModel = new UserRegistrationModel();

        $form = $this->createForm(UserRegistrationType::class,$registrationModel);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user = $registerService->registerUser($form->getData());


            return $handler->authenticateUserAndHandleSuccess(
              $user,
              $request,
              $loginFormAuthenticator,
              'main'
            );
        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);


    }
}
