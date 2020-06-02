<?php


namespace App\Service\TokenService;


class TokenGenerator implements TokenGeneratorInterface
{
    public static function generateToken(array $tokens,int $length): string
    {
        do {
            $token = bin2hex(\random_bytes($length));
        } while (isset($tokens[$token]));

        return $token;

    }

}