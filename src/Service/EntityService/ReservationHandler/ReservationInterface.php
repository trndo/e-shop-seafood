<?php

namespace App\Service\EntityService\ReservationHandler;

use App\Collection\ReservationCollection;
use App\Entity\Product;
use App\Entity\Reservation;
use App\Service\CartHandler\Item;

interface ReservationInterface
{
    /**
     * @param Product $product
     * @param bool $orderType
     * @param float $quantity
     * @return mixed
     */
    public function reserve(Product $product, bool $orderType, float $quantity): void ;

    /**
     * @param int $productId
     * @return Reservation
     */
    public function getReservation(int $productId): ?Reservation;

    /**
     * @param Item $item
     * @return mixed
     */
    public function deleteReservation(Item $item): void ;

    /**
     * @return ReservationCollection
     */
    public function getReservations(): ReservationCollection;
}