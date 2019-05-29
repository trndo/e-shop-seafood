<?php

namespace App\Collection;

use App\Entity\User;

/**
 * Class UserCollection
 * @package App\Collection
 */
class UserCollection implements \IteratorAggregate
{
    /**
     * @var User[]
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
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->users);
    }
}