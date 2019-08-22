<?php


namespace App\Service\RegistrationService;

use App\Entity\User;
use App\Model\AdminModel;
use App\Model\ConfirmationModelAdmin;
use App\Model\OrderModel;
use App\Model\UserRegistrationModel;

interface RegisterUserInterface
{
    /**
     * @param UserRegistrationModel $model
     *
     * Register user with UserRegistrationModel
     *
     * @return User
     */
    public function registerUser(UserRegistrationModel $model): User ;

    /**
     * User registration confirmation
     *
     * @param User $user
     */
    public function confirmUser(User $user): void ;

    /**
     * Register user with UserRegistrationModel
     *
     * @param AdminModel $model
     * @return User
     */
    public function registerAdmin(AdminModel $model): User ;

    /**
     * @param ConfirmationModelAdmin $model
     * @param User $admin
     * @return User
     *
     * Get admin data after registerAdmin
     */
    public function getRegisterAdminData(ConfirmationModelAdmin $model, User $admin): User ;

    /**
     * @param OrderModel $orderModel
     * @return User
     */
    public function registerUnknownUser(OrderModel $orderModel): User;
}