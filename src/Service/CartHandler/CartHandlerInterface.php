<?php


namespace App\Service\CartHandler;


interface CartHandlerInterface
{
    /**
     * @param string $type
     * @param string $slug
     */
    public function setToCart(string $type, string $slug): void;
}