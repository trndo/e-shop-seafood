<?php

namespace App\Repository;

use App\Entity\Supply;
use App\Repository\RepositoryInterface\FinderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Supply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supply[]    findAll()
 * @method Supply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyRepository extends ServiceEntityRepository implements FinderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Supply::class);
    }

    /**
     * @param string $productName
     * @return Supply[]|null
     */
    public function findForRender(string $productName): ?array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.product','p')
            ->addSelect('p')
            ->andWhere('p.name LIKE :productName ')
            ->setParameter('productName', '%'.$productName.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByName(string $name): ?array
    {
        // TODO: Implement findByName() method.
    }
}
