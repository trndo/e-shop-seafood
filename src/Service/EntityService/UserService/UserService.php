<?php

namespace App\Service\EntityService\UserService;

use App\Collection\UserCollection;
use App\Repository\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    private  $repository;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return UserCollection
     */
    public function getAdmins()
    {
        return new UserCollection($this->repository->findAdmins());
    }
}