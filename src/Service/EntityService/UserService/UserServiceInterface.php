<?php


namespace App\Service\EntityService\UserService;


use App\Collection\UserCollection;
use App\Entity\User;
use App\Model\AdminModel;

interface UserServiceInterface
{
    /**
     * @return UserCollection
     *
     * Get all admins
     */
    public function getAdmins(): UserCollection ;

    /**
     * @param string $token
     * @return User|null
     *
     * Get one user by user token
     */
    public function getUserByToken(string $token): ?User ;

    /**
     * @param User $user
     *
     * Delete User
     */
    public function deleteUser(User $user): void ;



}