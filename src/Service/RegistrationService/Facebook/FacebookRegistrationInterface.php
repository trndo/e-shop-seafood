<?php


namespace App\Service\RegistrationService\Facebook;

use App\Entity\User;
use League\OAuth2\Client\Provider\FacebookUser;

interface FacebookRegistrationInterface
{
    public function registerUser(FacebookUser $facebookUser): User ;
}