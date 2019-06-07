<?php

namespace App\Security;

use App\Entity\User;
use App\Security\SocialNetwork\AbstractSocialNetworkAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends AbstractSocialNetworkAuthenticator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * FacebookAuthenticator constructor.
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $entityManager
     * @param RouterInterface $router
     */
    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        parent::__construct($clientRegistry,$router);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return 'connect_facebook_check' === $request->attributes->get('_route')
            && $request->isMethod('GET');
    }

    /**
     * @param Request $request
     * @return AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|object|UserInterface|null
     */
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

    /**
     * @return OAuth2Client
     */
    private function getFacebookClient(): OAuth2Client
    {
       return parent::getSocialClient('facebook');
    }

}
