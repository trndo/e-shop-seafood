<?php


namespace App\Service\EntityService\ReservationHandler;


use App\Entity\Reservation;

interface ReservationInterface
{
    /**
     * @param string $productId
     * @param bool $orderType
     * @param float $quantity
     * @return mixed
     */
    public function reserve(string $productId, bool $orderType, float $quantity): void;

    /**
     * @param string $productId
     * @return Reservation
     */
    public function getReservation(string $productId): Reservation;

    /**
     * @param string $productId
     * @return mixed
     */
    public function deleteReservation(string $productId): void ;
}