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
     * @param string $id
     */
    public function getItem(string $id);

    /**
     * Add receipt or product to cart
     *
     * @param Request $request
     * @return array
     */
    public function addItemToCart(Request $request): array ;

    /**
     * Remove product or receipt from cart by key
     *
     * @param Request $request
     */
    public function removeFromCart(Request $request): void ;

    /**
     * Add item's quantity by key
     *
     * @param Request $request
     * @return array
     */
    public function changeItemQuantity(Request $request): array;


    /**
     * Return cart items
     *
     * @param Request $request
     * @return array
     */
    public function getItems(Request $request): array ;
}