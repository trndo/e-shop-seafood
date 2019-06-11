<?php


namespace App\Service\RegistrationService\SocialRegister;

use App\Entity\User;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class FacebookRegister implements SocialRegisterInterface
{

    public function registerUser(ResourceOwnerInterface $owner): User
    {
        if ($owner instanceof FacebookUser) {
            $user = new User();
            $user->setName($owner->getName())
                ->setSurname($owner->getLastName())
                ->setFacebookId($owner->getId())
                ->setRegistrationStatus(true);

            if ($owner->getEmail() != null) {
                $user->setEmail($owner->getEmail());
            }

            return $user;
        }
        throw new \InvalidArgumentException('Ty invalid nada facebookUser');
    }
}