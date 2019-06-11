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
                ->setRegistrationStatus(true);

            return $user;
        }
        throw new \InvalidArgumentException('Ty invalid nada instaUser');
    }
}