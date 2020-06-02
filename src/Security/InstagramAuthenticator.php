<?php

namespace App\Security;

use App\Entity\User;
use App\Security\SocialNetwork\AbstractSocialNetworkAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\InstagramResourceOwner;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class InstagramAuthenticator extends AbstractSocialNetworkAuthenticator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        parent::__construct($clientRegistry, $router);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return '/connect/instagram/check' === $request->getPathInfo()
            && $request->isMethod('GET');
    }

    /**
     * @param Request $request
     * @return AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getInstagramClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|object|UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var  InstagramResourceOwner $instagramId */
        $instagramUser = $this->getInstagramClient()
            ->fetchUserFromToken($credentials);

        $instagramId = $instagramUser->getId();

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy([
                'instagramId' => $instagramId
            ]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Такого пользвателя не сущевствует! Пожалуйста, зарегистрируйтесь');
        }

        return $user;
    }

    private function getInstagramClient(): OAuth2Client
    {
        return parent::getSocialClient('instagram');
    }
}
