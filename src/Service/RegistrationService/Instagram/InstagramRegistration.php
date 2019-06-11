<?php


namespace App\Service\RegistrationService\Instagram;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\InstagramResourceOwner;

class InstagramRegistration implements InstagramRegistrationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function registerUser(InstagramResourceOwner $instagramUser): User
    {
        $user = new User();
        $user->setInstagramId($instagramUser->getId())
            ->setName($instagramUser->getName())
            ->setRegistrationStatus(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

}