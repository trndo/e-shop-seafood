<?php


namespace App\Service\TokenService;


interface TokenGeneratorInterface
{
    /**
     * Generate unique token
     *
     * @param array $tokens - array of tokens
     * @return string
     */
    public static function generateToken(array $tokens): string;
}