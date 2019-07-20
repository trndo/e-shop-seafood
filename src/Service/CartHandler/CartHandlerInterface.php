<?php


namespace App\Service\CartHandler;


use App\Service\EntityService\SupplyService\SupplyServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface CartHandlerInterface
{
    /**
     * Get product or receipt
     *
     * @param string $type
     * @param string $id
     */
    public function getItem(string $type, string $id);

    /**
     * Add receipt or product to cart
     *
     * @param Request $request
     * @param string $key
     * @param array $options
     */
    public function addItemToCart(Request $request,string $key, array $options): void ;

    /**
     * Remove product or receipt from cart by key
     *
     * @param Request $request
     * @param string $key
     */
    public function removeFromCart(Request $request, string $key): void ;

    /**
     * Add item's quantity by key
     *
     * @param Request $request
     * @param string $key
     * @param float $quantity
     * @param string $type
     * @return array
     */
    public function changeItemQuantity(Request $request, string $key, float $quantity): array;
}