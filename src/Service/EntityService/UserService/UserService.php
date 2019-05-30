<?php

namespace App\Service\EntityService\UserService;

use App\Collection\UserCollection;
use App\Entity\User;
use App\Mapper\UserMapper;
use App\Model\AdminModel;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
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

    public function deleteUser(User $user): void
    {
        if ($user instanceof User) {
            $this->repository->delete($user);
        }
    }




}