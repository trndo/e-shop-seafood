<?php


namespace App\Service\TokenService;


class TokenGenerator implements TokenGeneratorInterface
{
    public static function generateToken(array $tokens): string
    {
        do {
            $token = bin2hex(\random_bytes(60));
        } while (isset($tokens[$token]));

        return $token;

    }

}