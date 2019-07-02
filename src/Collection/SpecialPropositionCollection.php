<?php


namespace App\Collection;


use Traversable;

class SpecialPropositionCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $specialPropositions;

    /**
     * SpecialPropositionCollection constructor.
     * @param array $specialPropositions
     */
    public function __construct(array $specialPropositions)
    {
        $this->specialPropositions = $specialPropositions;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->specialPropositions);
    }
}