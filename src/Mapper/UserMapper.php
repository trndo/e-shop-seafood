<?php


namespace App\Mapper;

use App\Entity\User;
use App\Model\AdminModel;
use App\Model\UserModel;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserMapper
{
    public static function entityToAdminModel(User $user): AdminModel
    {
        $model = new AdminModel();

        $model->setName($user->getName())
             ->setSurname($user->getSurname())
             ->setPhone($user->getPhone())
             ->setEmail($user->getEmail());

        return $model;
    }

    public static function entityToUserModel(User $user): UserModel
    {
        $model = new UserModel() ;

        $model->setName($user->getName())
            ->setPhone($user->getPhone())
            ->setEmail($user->getEmail())
            ->setAddress($user->getAddress())
            ->setCoordinates($user->getCoordinates());

        return $model;
    }

    public static function userModelToEntity(UserModel $model, User $user): User
    {
        $user->setName($model->getName())
            ->setPhone($model->getPhone())
            ->setEmail($model->getEmail())
            ->setAddress($model->getAddress())
            ->setCoordinates($model->getCoordinates());

        return $user;
    }


}