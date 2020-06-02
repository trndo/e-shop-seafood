<?php


namespace App\Service\RegistrationService\SocialRegisterFactory;


use App\Service\RegistrationService\SocialRegister\FacebookRegister;
use App\Service\RegistrationService\SocialRegister\GoogleRegister;
use App\Service\RegistrationService\SocialRegister\InstagramRegister;
use App\Service\RegistrationService\SocialRegister\SocialRegisterInterface;

final class SocialRegisterStaticFactory
{
    public static function factory(string $socialScope):SocialRegisterInterface
    {
        switch ($socialScope) {
            case 'google':
                return new GoogleRegister();
            case 'facebook':
                return new FacebookRegister();
            case 'instagram':
                return new InstagramRegister();
            default:
                throw new \InvalidArgumentException('Unknown scope given');
        }

    }
}