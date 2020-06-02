<?php

namespace App\Collection;

use App\Entity\Receipt;

/**
 * Class ReceiptCollection
 * @package App\Collection
 */
class ReceiptCollection implements \IteratorAggregate
{
    /**
     * @var Receipt[]
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

    public function toArray()
    {
        return $this->receipts;
    }
}