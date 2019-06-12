<?php


namespace App\Service\RegistrationService\SocialRegister;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class GoogleRegister implements SocialRegisterInterface
{

    public function registerUser(ResourceOwnerInterface $owner): User
    {
        if ($owner instanceof GoogleUser) {
            $user = new User();
            $user->setEmail($owner->getEmail())
                ->setName($owner->getName())
                ->setSurname($owner->getLastName())
                ->setGoogleId($owner->getId())
                ->setRegistrationStatus(true);

            return $user;
        }
        throw new \InvalidArgumentException('Ty invalid nada googleuser');
    }

}