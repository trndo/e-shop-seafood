<?php

namespace App\Repository;

use App\Entity\OrderInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @param int $userId
     * @return OrderInfo[]|null
     */
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

    /**
     * @param string $status
     * @return OrderInfo[]|null
     */
    public function getOrders(string $status = 'new'): ?array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.orderDetails', 'od')
            ->leftJoin('od.receipt','r')
            ->leftJoin('od.product','p')
            ->leftJoin('p.supply','s')
            ->leftJoin('p.gift','g')
            ->leftJoin('o.user', 'u')
            ->addSelect( 'u','od','r','p','s','g')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status)
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array|null
     */
    public function getOrderStatusCount(): ?array
    {
        return $this->createQueryBuilder('o','o.status')
            ->select('o','count(o.status)')
            ->groupBy('o.status')
            ->orderBy('o.id','ASC')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
