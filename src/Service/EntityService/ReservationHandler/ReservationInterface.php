<?php


namespace App\Service\EntityService\ReservationHandler;


use App\Collection\ReservationCollection;
use App\Entity\Reservation;

interface ReservationInterface
{
    /**
     * @param string $productId
     * @param bool $orderType
     * @param float $quantity
     * @return mixed
     */
    public function reserve(string $productId, bool $orderType, float $quantity): void ;

    /**
     * @param string $productId
     * @return Reservation
     */
    public function getReservation(?string $productId): ?Reservation;

    /**
     * @param array $cart
     * @param string $key
     * @return mixed
     */
    public function deleteReservation(array $cart,string $key): void ;

    public function getReservations(): ReservationCollection;
}