<?php


namespace App\Service\RegistrationService\Google;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;

class GoogleRegistration implements GoogleRegistrationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function registerUser(GoogleUser $googleUser): User
    {
        $user = new User();
        $user->setEmail($googleUser->getEmail())
            ->setName($googleUser->getName())
            ->setSurname($googleUser->getLastName())
            ->setGoogleId($googleUser->getId())
            ->setRegistrationStatus(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;

    }
}