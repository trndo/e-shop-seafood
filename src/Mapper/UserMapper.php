<?php


namespace App\Mapper;

use App\Entity\User;
use App\Model\AdminModel;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserMapper
{
    public static function entityToAdminModel (User $user): AdminModel
    {
        $model = new AdminModel();

        $model->setName($user->getName())
             ->setSurname($user->getSurname())
             ->setPhone($user->getPhone())
             ->setEmail($user->getEmail());

        return $model;
    }

}