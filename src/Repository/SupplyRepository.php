<?php

namespace App\Repository;

use App\Entity\Supply;
use App\Repository\RepositoryInterface\FinderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
        return $this->createQueryBuilderForSuppliesProducts('s')
            ->andWhere('p.name LIKE :productName AND p.isDeletable IS NULL')
            ->setParameter('productName', '%'.$productName.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllSupplies(): ?array
    {
        return $this->createQueryBuilderForSuppliesProducts('s')
            ->addOrderBy('s.quantity','DESC')
            ->andWhere('p.isDeletable IS NULL')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSuppliesBy(int $category = null): ?array
    {
        $query = $this->createQueryBuilderForSuppliesProducts('s');
        if ($category != null){
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        return $query->andWhere('p.isDeletable IS NULL')
            ->orderBy('p.status','ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByName(string $name): ?array
    {
        // TODO: Implement findByName() method.
    }

    private function createQueryBuilderForSuppliesProducts(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->leftJoin($alias.'.product','p')
            ->leftJoin('p.gift','g')
            ->leftJoin('p.category','c')
            ->leftJoin('p.orderDetail','od')
            ->addSelect('p','g','c','od');

    }

}
