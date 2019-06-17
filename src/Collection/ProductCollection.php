<?php

namespace App\Collection;

use App\Entity\Product;

class ProductCollection implements \IteratorAggregate
{
    /**
     * @var Product[] $products
     */
    private $products;

    /**
     * ProductCollection constructor.
     * @param array $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->products);
    }
}