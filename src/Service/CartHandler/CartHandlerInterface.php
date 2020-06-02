<?php


namespace App\Service\CartHandler;


use App\Service\EntityService\SupplyService\SupplyServiceInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface CartHandlerInterface
{

    /**
     * Add receipt or product to cart
     *
     * @param ParameterBag $bag
     * @return array
     */
    public function addItemToCart(ParameterBag $bag): array ;

    /**
     * Remove product or receipt from cart by key
     *
     * @param ParameterBag $bag
     */
    public function removeFromCart(ParameterBag $bag): void;

    /**
     * Add item's quantity by key
     *
     * @param ParameterBag $bag
     * @return array
     */
    public function changeItemQuantity(ParameterBag $bag): array;


    /**
     * Return cart items
     *
     * @return array
     */
    public function getItems(): array ;
}