<?php


namespace App\Service\RegistrationService\Google;


use App\Entity\User;
use League\OAuth2\Client\Provider\GoogleUser;

interface GoogleRegistrationInterface
{
    public function registerUser(GoogleUser $googleUser): User ;
}