<?php


namespace App\Service\CartHandler;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface CartHandlerInterface
{
    /**
     * Get product or receipt
     *
     * @param string $type
     * @param string $slug
     */
    public function getItem(string $type, string $slug);

    /**
     * Add receipt or product to cart
     *
     * @param Request $request
     * @param string $key
     * @param array $options
     */
    public function addItemToCart(Request $request,string $key, array $options): void ;

    /**
     * Remove product or receipt from cart
     *
     * @param Request $request
     * @param string $key
     */
    public function removeFromCart(Request $request, string $key): void ;
}