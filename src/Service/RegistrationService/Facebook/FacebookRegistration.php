<?php


namespace App\Service\RegistrationService\Facebook;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\FacebookUser;

class FacebookRegistration implements FacebookRegistrationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function registerUser(FacebookUser $facebookUser): User
    {
        $user = new User();
        $user->setName($facebookUser->getName())
            ->setSurname($facebookUser->getLastName())
            ->setFacebookId($facebookUser->getId())
            ->setRegistrationStatus(true);

        if ($facebookUser->getEmail() != null) {
            $user->setEmail($facebookUser->getEmail());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}