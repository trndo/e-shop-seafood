<?php

namespace App\Repository;

use App\Entity\Receipt;
use App\Repository\RepositoryInterface\FinderInterface;
use App\Repository\RepositoryInterface\RatingInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Receipt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Receipt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Receipt[]    findAll()
 * @method Receipt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiptRepository extends ServiceEntityRepository implements FinderInterface, RatingInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Receipt::class);
    }

    public function findByName(string $receiptName): ?array
    {
        return $this->createQueryBuilder('r')
            ->select('r.name')
            ->andWhere('r.name LIKE :receiptName AND r.isDeletable IS NULL')
            ->setParameter('receiptName', '%' . $receiptName . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }

    public function findForRating(): ?array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.category', 'category')
            ->leftJoin('r.orderDetail', 'orderDetail')
            ->addSelect('category, orderDetail')
            ->andWhere('r.rating != 0 AND r.status = true AND r.isDeletable IS NULL')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $productName
     * @return array|null
     */
    public function findForRender(string $productName): ?array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('r')
            ->andWhere('r.name LIKE :productName')
            ->setParameter('productName', '%' . $productName . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findReceiptBySlug(string $slug): ?Receipt
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @param int $categoryId
     * @param bool $setMaxResults
     * @return Receipt[]|null
     */
    public function getReceiptsFromCategory(int $categoryId, bool $setMaxResults = false): ?array
    {
        $query = $this->createQueryBuilder('r')
            ->addSelect('c', 'od')
            ->leftJoin('r.category', 'c')
            ->leftJoin('r.orderDetail', 'od')
            ->andWhere('r.status = true AND c.id = :categoryId AND r.isDeletable IS NULL')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('r.category', 'ASC');

            if ($setMaxResults) {
                $query->setMaxResults(8);
            }

          return  $query->getQuery()
                       ->getResult();
    }


    public function getReceiptsForLoading(int $categoryId, int $count, $offset = 9): ?array
    {
        $query =  $this->createQueryBuilder('r')
            ->addSelect('c', 'od')
            ->leftJoin('r.category', 'c')
            ->leftJoin('r.orderDetail', 'od')
            ->andWhere('r.status = true AND c.id = :categoryId AND r.isDeletable IS NULL')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('r.category', 'ASC')
            ->setFirstResult($count)
            ->setMaxResults($offset)
            ->getQuery()
            ->getResult();

        return $query;
    }

    public function findReceiptsBy(string $name = null, int $category = null, bool $includeStatus = false): ?array
    {
        $query = $this->createQueryBuilderForReceipt('r');

        if($includeStatus)
            $query->where('r.status = 1');

        if ($name != null){
            $query->andWhere('r.name = :name')
                ->setParameter('name', $name);
        }
        if ($category != null){
            $query->andWhere('r.category = :category')
                ->setParameter('category', $category);
        }
        return $query->andWhere('r.isDeletable IS NULL')
            ->orderBy('r.status','ASC')
            ->getQuery()
            ->getResult();
    }

    private function createQueryBuilderForReceipt(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->addSelect('c', 'od')
            ->leftJoin($alias.'.category', 'c')
            ->leftJoin($alias.'.orderDetail', 'od');
    }

    public function findById(int $id): ?Receipt
    {
        return $this->createQueryBuilderForReceipt('r')
                ->andWhere('r.id = :id')
                ->setParameter('id',$id)
                ->getQuery()
                ->getOneOrNullResult();
    }
}
