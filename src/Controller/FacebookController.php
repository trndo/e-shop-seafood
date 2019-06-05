<?php


namespace App\Controller;


use App\Service\RegistrationService\Facebook\FacebookRegistrationInterface;
use App\Service\RegistrationService\Google\GoogleRegistrationInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    /**
     * @Route("/connect/facebook", name="connect_facebook_start")
     *
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connectAction(ClientRegistry $clientRegistry): Response
    {

        return $clientRegistry
            ->getClient('facebook')
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ])
            ;
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     *
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
     * @Route("/connect/register/facebook", name="connect_facebook_register")
     *
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connectRegister(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('facebook_register')
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ])
            ;
    }

    /**
     * @Route("/register/facebook", name="facebookRegister")
     *
     * @param FacebookRegistrationInterface $facebookRegistration
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function registerUserWithGoogle(FacebookRegistrationInterface $facebookRegistration ,ClientRegistry $clientRegistry): Response
    {
        /** @var FacebookClient $client */
        $client = $clientRegistry->getClient('facebook_register');

        /** @var FacebookUser $user */
        $user = $client->fetchUser();

        if ($user instanceof FacebookUser) {
            $facebookRegistration->registerUser($user);
        }

        return $this->redirectToRoute('connect_facebook_start');

    }
}