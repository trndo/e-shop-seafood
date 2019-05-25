<?php

namespace App\Service\EntityService\UserService;

use App\Collection\UserCollection;
use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    private $repository;

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
    public function getAdmins(): UserCollection
    {
        return new UserCollection($this->repository->findAdmins());
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function getUserByToken(string $token): ?User
    {
        return $this->repository->findOneBy(['token' => $token]);
    }

}