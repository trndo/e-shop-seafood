<?php

namespace App\Collection;

/**
 * Class ReceiptCollection
 * @package App\Collection
 */
class ReceiptCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $receipts;

    /**
     * ReceiptCollection constructor.
     * @param array $receipts
     */
    public function __construct(array $receipts)
    {
        $this->receipts = $receipts;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->receipts);
    }
}