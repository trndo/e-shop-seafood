<?php

namespace App\Controller;

use App\Service\RegistrationService\Instagram\InstagramRegistrationInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\InstagramClient;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\InstagramResourceOwner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstagramController extends AbstractController
{
//    /**
//     * @Route("/connect/instagram", name="connect_instagram_start")
//     *
//     * @param ClientRegistry $clientRegistry
//     * @return Response
//     */
//    public function connectAction(ClientRegistry $clientRegistry): Response
//    {
//
//        return $clientRegistry
//            ->getClient('instagram')
//            ->redirect([
//                'basic' // the scopes you want to access
//            ])
//            ;
//    }
//
//    /**
//     * @Route("/connect/instagram/check", name="connect_instagram_check")
//     *
//     * @param Request $request
//     * @param ClientRegistry $clientRegistry
//     * @return Response
//     */
//    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): Response
//    {
//        if (!$this->getUser()) {
//            return new JsonResponse(array('status' => false, 'message' => "User not found!"));
//        } else {
//            return $this->redirectToRoute('home');
//        }
//    }
//
//    /**
//     * @Route("/connect/instagram/register", name="connect_instagram_register")
//     *
//     * @param ClientRegistry $clientRegistry
//     * @return Response
//     */
//    public function connectRegister(ClientRegistry $clientRegistry): Response
//    {
//        return $clientRegistry
//            ->getClient('instagram_register')
//            ->redirect([
//                'basic'
//            ])
//            ;
//    }
//
//    /**
//     * @Route("register/instagram", name="registerInstagram")
//     *
//     * @param InstagramRegistrationInterface $instagramRegistration
//     * @param ClientRegistry $clientRegistry
//     * @return Response
//     */
//    public function registerUserWithInstagram(InstagramRegistrationInterface $instagramRegistration, ClientRegistry $clientRegistry): Response
//    {
//        /** @var InstagramClient $client */
//        $client = $clientRegistry->getClient('instagram_register');
//
//        /** @var InstagramResourceOwner $user */
//        $user = $client->fetchUser();
//
//        if ($user instanceof InstagramResourceOwner) {
//            $instagramRegistration->registerUser($user);
//        }
//
//        return $this->redirectToRoute('connect_instagram_start');
//    }
}