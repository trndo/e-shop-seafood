<?php


namespace App\Collection;


use App\Entity\Reservation;
use Traversable;

class ReservationCollection implements \IteratorAggregate
{
    /**
     * @var Reservation[]
     */
    private $reservations;

    /**
     * ReservationCollection constructor.
     * @param array $reservations
     */
    public function __construct(array $reservations)
    {
        $this->reservations = $reservations;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->reservations);
    }
}