<?php


namespace App\Service\EntityService\UserService;


use App\Collection\UserCollection;
use App\Entity\User;
use App\Model\AdminModel;
use App\Model\OrderModel;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    public function getUserByToken(?string $token): ?User ;

    /**
     * @param User $user
     *
     * Delete User
     */
    public function deleteUser(?User $user): void ;

    /**
     * @param User $user
     *
     * Save User
     */
    public function saveUser(?User $user): void ;

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
    public function deleteUserById(?User $user): void ;

    /**
     * @param User $user
     * @param int $bonuses
     */
    public function saveBonuses(?User $user, ?int $bonuses): void;

    /**
     * @param User $user
     * @return User
     */
    public function resetPassword(?User $user): User ;

    /**
     * @param string $email
     * @return User
     */
    public function findUserByEmail(?string $email): User ;

    /**
     * @param $user
     * @param $newPassword
     * @param $oldPassword
     */
    public function resetOldPassword(?User $user,?string $newPassword,?string $oldPassword): void ;

    /**
     * @param $user
     * @param $password
     */
    public function addNewPassword(?User $user,?string $password): void ;

    /**
     * @param string|null $passToken
     * @return User
     */
    public function getUserByPassToken(?string $passToken): User ;

    /**
     * @param User $user
     * @param OrderModel $model
     * @return User
     */
    public function setEmptyPropertiesOfUser(User $user, OrderModel $model): User;

    /**
     * @return void
     */
    public function updateUserInfo(): void ;




}