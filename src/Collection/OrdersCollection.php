<?php


namespace App\Collection;


use App\Entity\OrderInfo;
use Traversable;

class OrdersCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var OrderInfo[]
     */
    private $orders;

    /**
     * OrdersCollection constructor.
     * @param array $orders
     */
    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->orders);
    }


    /**
     * @inheritDoc
     */
    public function count()
    {
        return \count($this->orders);
    }
}