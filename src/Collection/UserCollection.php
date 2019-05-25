<?php

namespace App\Collection;

/**
 * Class UserCollection
 * @package App\Collection
 */
class UserCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $users;

    /**
     * UserCollection constructor.
     * @param array $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->users);
    }
}