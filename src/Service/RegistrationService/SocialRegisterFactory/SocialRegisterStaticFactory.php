<?php


namespace App\Service\RegistrationService\SocialRegisterFactory;


use App\Service\RegistrationService\SocialRegister\GoogleRegister;
use App\Service\RegistrationService\SocialRegister\SocialRegisterInterface;

final class SocialRegisterStaticFactory
{
    public static function factory(string $socialScope):SocialRegisterInterface
    {
        switch ($socialScope) {
            case 'google':
                return new GoogleRegister();
            case 'facebook':
            case 'instagram':
            default:
                throw new \InvalidArgumentException('Unknown scope given');
        }

    }
}