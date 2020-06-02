<?php


namespace App\Service\ScopeService;


class SocialNetworkScopeService implements SocialNetworkScopeServiceInterface
{
    public function getSocialScope(string $socialNetwork): array
    {
        switch ($socialNetwork) {
            case 'facebook': case 'facebook_register':
                return [
                    'public_profile', 'email'
                ];
            case 'instagram': case 'instagram_register':
                return [
                    'basic'
                ];
            case 'google': case 'google_register':
                return [];
            default:
                break;
        }
    }
}