<?php


namespace App\Collection;


use App\Entity\Supply;
use Traversable;

class SupplyCollection implements \IteratorAggregate
{
    /**
     * @var Supply[]
     */
    private $supplies;

    /**
     * SupplyCollection constructor.
     * @param array $supplies
     */
    public function __construct(array $supplies)
    {
        $this->supplies = $supplies;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->supplies);
    }
}