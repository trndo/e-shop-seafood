<?php


namespace App\Service\ScopeService;


interface SocialNetworkScopeServiceInterface
{
    /**
     * Get social scopes for connect
     *
     * @param string $socialNetwork
     * @return array
     */
    public function getSocialScope(string $socialNetwork): array ;
}