<?php


namespace App\Controller;


use App\Service\EntityService\UserService\UserService;
use App\Service\RegistrationService\Google\GoogleRegistrationInterface;
use App\Service\RegistrationService\SocialRegisterFactory\SocialRegisterStaticFactory;
use App\Service\ScopeService\SocialNetworkScopeServiceInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SocialNetworkController extends AbstractController
{
    /**
     * @Route("/connect/{social}", name="connectAction")
     * @param ClientRegistry $clientRegistry
     * @param SocialNetworkScopeServiceInterface $scopeService
     * @param string $social
     * @return Response
     */
    public function connectAction(ClientRegistry $clientRegistry,SocialNetworkScopeServiceInterface $scopeService, ?string $social): Response
    {
        return $clientRegistry
            ->getClient($social)
            ->redirect(
                $scopeService->getSocialScope($social)
            );
    }

    /**
     * @Route("/connect/{social}/register", name="connectRegister")
     *
     * @param ClientRegistry $clientRegistry
     * @param SocialNetworkScopeServiceInterface $scopeService
     * @param string $social
     * @return Response
     */
    public function connectRegister(ClientRegistry $clientRegistry, SocialNetworkScopeServiceInterface $scopeService,?string $social): Response
    {
        $socialRegister = $social.'_register';

        return $clientRegistry
            ->getClient($socialRegister)
            ->redirect(
                $scopeService->getSocialScope($socialRegister)
            )
            ;
    }

    /**
     * @Route("/connect/{social}/check", name="connectCheck")
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
     * @Route("/register/{social}", name="socialRegister")
     *
     * @param UserService $userService
     * @param ClientRegistry $clientRegistry
     * @param string $social
     * @return Response
     */
    public function registerUserWithSocialNetwork(UserService $userService, ClientRegistry $clientRegistry,?string $social): Response
    {
        $socialRegister = $social.'_register';

        $client = $clientRegistry->getClient($socialRegister);
        $user = $client->fetchUser();

        $socialUser = SocialRegisterStaticFactory::factory($social)->registerUser($user);
        $userService->saveUser($socialUser);

        return $this->redirectToRoute('connectAction',[
            'social' => $social
        ]);
   }
}