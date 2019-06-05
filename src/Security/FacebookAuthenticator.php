<?php

namespace App\Security;

use App\Entity\User;
use App\Security\SocialNetwork\AbstractSocialNetworkAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FacebookAuthenticator extends AbstractSocialNetworkAuthenticator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        parent::__construct($clientRegistry,$router);
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request)
    {
        return 'connect_facebook_check' === $request->attributes->get('_route')
            && $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $facebookId = $facebookUser->getId();

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['facebookId' => $facebookId]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Такого пользвателя не сущевствует! Пожалуйста, зарегистрируйтесь');
        }

        return $user;
    }

    private function getFacebookClient()
    {
       return parent::getSocialClient('facebook');
    }

}
