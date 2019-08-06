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
     * @param Item $item
     * @return mixed
     */
    public function reserve(Product $product, Item $item): void ;

    /**
     * @param string $key
     * @return Reservation
     */
    public function getReservation(string $key): ?Reservation;

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