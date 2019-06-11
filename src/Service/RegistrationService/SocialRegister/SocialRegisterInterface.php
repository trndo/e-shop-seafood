<?php

namespace App\Service\RegistrationService\SocialRegister;

use App\Entity\User;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

interface SocialRegisterInterface
{
    public function registerUser(ResourceOwnerInterface $owner): User;
}