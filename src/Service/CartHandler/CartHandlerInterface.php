<?php


namespace App\Service\CartHandler;


interface CartHandlerInterface
{
    /**
     * @param string $type
     * @param string $slug
     */
    public function getItem(string $type, string $slug);
}