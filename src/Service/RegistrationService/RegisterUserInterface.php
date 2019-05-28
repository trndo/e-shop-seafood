<?php


namespace App\Service\RegistrationService;

use App\Entity\User;
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
    public function registerUser(UserRegistrationModel $model): User;

    /**
     * User registration confirmation
     *
     * @param User $user
     */
    public function confirmUser(User $user): void;
}