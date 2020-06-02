<?php


namespace App\Service\RegistrationService\SocialRegister;


use App\Entity\User;
use League\OAuth2\Client\Provider\InstagramResourceOwner;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class InstagramRegister implements SocialRegisterInterface
{

    public function registerUser(ResourceOwnerInterface $owner): User
    {
        if ($owner instanceof InstagramResourceOwner) {
            $user = new User();
            $user->setInstagramId($owner->getId())
                ->setName($owner->getName())
                ->setRegistrationStatus(true)
                ->setUniqueId($user->generateUniqueId(6));

            return $user;
        }
        throw new \InvalidArgumentException('Неправильный Instagram client');
    }
}