<?php

namespace App\Repository;

use App\Entity\OrderInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderInfo[]    findAll()
 * @method OrderInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderInfo::class);
    }


    /**
     * @param int $id
     * @return OrderInfo
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOrderById(?int $id): ?OrderInfo
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.orderDetails', 'od')
            ->addSelect('od')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getOrdersByUserId(int $userId): ?array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.orderDetails', 'od')
            ->leftJoin('o.user', 'u')
            ->addSelect('od', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
