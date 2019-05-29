<?php


namespace App\Collection;


use App\Entity\Category;

class CategoryCollection implements \IteratorAggregate
{
    /**
     * @var Category[]
     */
    private $categories;


    /**
     * CategoryCollection constructor.
     * @param array $categories
     */
    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->categories);
    }


}