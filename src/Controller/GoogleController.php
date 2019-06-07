<?php


namespace App\Controller;


use App\Service\RegistrationService\Facebook\FacebookRegistrationInterface;
use App\Service\RegistrationService\Google\GoogleRegistrationInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect_google")
     *
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry->
            getClient('google')
            ->redirect();
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): Response
    {
        if (!$this->getUser()) {
            return new JsonResponse(array('status' => false, 'message' => "User not found!"));
        } else {
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/connect/google/register", name="connect_google_register")
     *
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connectRegister(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('google_register')
            ->redirect();
    }

    /**
     * @Route("/register/google", name="googleRegister")
     *
     * @param GoogleRegistrationInterface $googleRegistration
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function registerUserWithGoogle(GoogleRegistrationInterface $googleRegistration ,ClientRegistry $clientRegistry): Response
    {
        /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google_register');

        /** @var  GoogleUser $user */
        $user = $client->fetchUser();

        if ($user instanceof GoogleUser) {
            $googleRegistration->registerUser($user);
        }

        return $this->redirectToRoute('connect_google');

    }


}