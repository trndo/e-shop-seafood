<?php


namespace App\Controller;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
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
     * @param ClientRegistry $clientRegistry
     *
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->
            getClient('google')
            ->redirect();
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     * @param Request $request
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): Response
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient $client **/
        $client = $clientRegistry->getClient('google');

        /** @var GoogleUser $user */
        $user = $client->fetchUser();


        if (!$this->getUser()) {
            return new JsonResponse(array('status' => false, 'message' => "User not found!"));
        } else {
            return $this->redirectToRoute('home');
        }

    }


}