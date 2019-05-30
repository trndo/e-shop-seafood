<?php


namespace App\Service\TokenService;


interface TokenGeneratorInterface
{
    /**
     * Generate unique token
     *
     * @param array $tokens - array of tokens
     * @param int $length
     * @return string
     */
    public static function generateToken(array $tokens, int $length): string;
}