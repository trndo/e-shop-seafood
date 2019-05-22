<?php


namespace App\Service\Registration;

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
}