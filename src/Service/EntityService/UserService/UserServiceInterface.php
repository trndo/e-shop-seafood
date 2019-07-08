<?php


namespace App\Service\EntityService\UserService;


use App\Collection\UserCollection;
use App\Entity\User;
use App\Model\AdminModel;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

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

    /**
     * @param User $user
     *
     * Save User
     */
    public function saveUser(User $user): void ;

    /**
     * Return users collection with ROLE_USER
     *
     * @return UserCollection
     */
    public function getUsers(): UserCollection;

    /**
     * Delete user by id
     *
     * @param User $user
     */
    public function deleteUserById(User $user): void ;

}