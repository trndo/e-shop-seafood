<?php


namespace App\Service\CartHandler;


class CartHandler implements CartHandlerInterface
{
    /**
     * @param string $type
     * @param string $slug
     */
    public function setToCart(string $type, string $slug): void
    {
        if ($type === 'product') {

        }
    }
}