<?php


namespace App\Mapper;


use App\Entity\OrderInfo;
use App\Entity\User;
use App\Model\OrderModel;

final class OrderMapper
{
    public static function entityUserToOrderModel(User $user): OrderModel
    {
        $model = new OrderModel();

        $model->setName($user->getName())
            ->setSurname($user->getSurname())
            ->setEmail($user->getEmail())
            ->setPhoneNumber($user->getPhone())
            ->setDeliveryType($user->getAddress())
            ->setUser($user);

        return $model;
    }

    public static function entityToModel(OrderInfo $info): OrderModel
    {
        $model = new OrderModel();

        return $model->setOrderDate($info->getOrderDate())
                ->setOrderTime($info->getOrderTime())
                ->setOrderDetails($info->getOrderDetails())
                ->setTotalPrice($info->getTotalPrice());
    }

    public static function orderModelToEntity(OrderModel $model): OrderInfo
    {
        $entity = new OrderInfo();

        $entity->setOrderDate($model->getOrderDate())
            ->setOrderTime($model->getOrderTime())
            ->setCreatedAt(new \DateTime())
            ->setUser($model->getUser())
            ->setStatus('new')
            ->setTotalPrice($model->getTotalPrice())
            ->setOrderPhone($model->getPhoneNumber())
            ->setOrderEmail($model->getEmail())
            ->setAddress($model->getDeliveryType())
            ->setCoordinates($model->getCoordinates());

        return $entity;

    }
}