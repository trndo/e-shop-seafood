<?php


namespace App\Service\RegistrationService\Instagram;


use App\Entity\User;
use League\OAuth2\Client\Provider\InstagramResourceOwner;

interface InstagramRegistrationInterface
{
    public function registerUser(InstagramResourceOwner $instagramUser): User ;
}