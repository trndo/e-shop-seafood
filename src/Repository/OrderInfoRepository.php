<?php

namespace App\Repository;

use App\Collection\OrdersCollection;
use App\Entity\OrderInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
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
        return $this->createQueryBuilderForOrderInfo('o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getOrderByUniqueId(?int $orderUniqueId): ?OrderInfo
    {
        return $this->createQueryBuilderForOrderInfo('o')
            ->andWhere('o.orderUniqueId = :orderUniqueId')
            ->setParameter('orderUniqueId', $orderUniqueId)
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
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getOrdersForAdmin(string $date, string $status = 'new'): ?array
    {
        $query = $this->createQueryBuilderForOrderInfo('o');
        $query->andWhere('o.status = :status AND o.orderDate = :date')
            ->setParameters([
                'status' => $status,
                'date' => $date
            ]);

        return $query->orderBy('o.orderDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getOrders(string $date, string $status = 'new'): ?array
    {
        $query = $this->createQueryBuilderForOrderInfo('o');

        if ($date == (new \DateTime())->format('Y-m-d')) {

            $query->andWhere('o.status = :status AND o.orderDate = :date')
                ->setParameters([
                    'status' => $status,
                    'date' => $date
                ]);
        } else {

            $query->andWhere('o.status = :status AND o.orderDate != :date')
                ->setParameters([
                    'status' => $status,
                    'date' => (new \DateTime())->format('Y-m-d')
                ]);

        }
        return $query->orderBy('o.orderDate', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function getAllOrders(): ?array
    {
        return $this->createQueryBuilderForOrderInfo('o')
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getOrdersForAnotherDay(string $date, string $status = 'payed'): ?array
    {
        $query = $this->createQueryBuilderForOrderInfo('o');

        return $query->andWhere('o.status = :status AND o.orderDate = :date')
            ->setParameters([
                'status' => $status,
                'date' => $date
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $date
     * @return array|null
     */
    public function getOrderStatusCount(string $date = null): ?array
    {
        return $this->createQueryBuilder('o', 'o.status')
            ->addSelect('count(o.status)')
            ->groupBy('o.status')
            ->andWhere('o.orderDate = :date')
            ->orderBy('o.id', 'ASC')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

    }

    private function createQueryBuilderForOrderInfo(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->leftJoin($alias . '.orderDetails', 'od')
            ->leftJoin('od.receipt', 'r')
            ->leftJoin('od.product', 'p')
            ->leftJoin('p.supply', 's')
            ->leftJoin('p.gift', 'g')
            ->leftJoin($alias . '.user', 'u')
            ->addSelect('u', 'od', 'r', 'p', 's', 'g');
    }
}
