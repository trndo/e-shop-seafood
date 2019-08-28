<?php

namespace App\Repository;

use App\Entity\Product;
use App\Repository\RepositoryInterface\FinderInterface;
use App\Repository\RepositoryInterface\RatingInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements FinderInterface, RatingInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $productName
     * @return array
     */
    public function findByName(string $productName)
    {
        return $this->createQueryBuilder('p')
            ->select('p.name')
            ->andWhere('p.name LIKE :productName')
            ->setParameter('productName', '%' . $productName . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }

    public function findForRating(): ?array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.supply', 'supply')
            ->leftJoin('p.category', 'category')
            ->leftJoin('p.gift', 'gift')
            ->leftJoin('p.specialPropositions', 'specialPropositions')
            ->leftJoin('p.orderDetail', 'orderDetail')
            ->addSelect('p, supply, category, specialPropositions, gift, orderDetail')
            ->andWhere('p.rating != 0 AND p.status = true')
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
        return $this->createQueryBuilder('p')
            ->addSelect('p')
            ->andWhere('p.name LIKE :productName ')
            ->setParameter('productName', '%' . $productName . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findProductBySlug(string $slug): Product
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @param int $categoryId
     * @param bool $setMaxResults
     * @return array|null
     */
    public function getProductsFromCategory(int $categoryId, bool $setMaxResults = false): ?array
    {
        $query = $this->createQueryBuilderForProduct('p')
            ->andWhere('p.status = true AND c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.category', 'ASC');

            if ($setMaxResults) {
                $query->setMaxResults(8);
            }

        return $query->getQuery()
                  ->execute();

    }

    public function getProductsForLoading(int $categoryId, int $count, int $offset = 9): ?array
    {
        $query = $this->createQueryBuilderForProduct('p')
            ->andWhere('p.status = true AND c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.category', 'ASC')
            ->setFirstResult($count)
            ->setMaxResults($offset)
            ->getQuery()
            ->getResult();

        return $query;
    }

    public function findProductsBy(string $name = null, int $category = null): ?array
    {
         $query = $this->createQueryBuilderForProduct('p');
         if ($name != null){
             $query->andWhere('p.name = :name')
                ->setParameter('name', $name);
         }
        if ($category != null){
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }
         return $query->orderBy('p.status','ASC')
             ->getQuery()
             ->getResult();
    }



//    public function findById(int $id): ?Product
//    {
//        return $this->createQueryBuilderForProduct('p')
//            ->andWhere('p.status = true AND p.id = :id')
//            ->setParameter('id',$id)
//            ->getQuery()
//            ->getOneOrNullResult();
//    }

    private function createQueryBuilderForProduct(string $alias): QueryBuilder
    {
        if ($alias) {
            return $this->createQueryBuilder($alias)
                ->addSelect('c', 'od', 'ap', 'g', 'sp', 's')
                ->leftJoin($alias.'.additionalProduct', 'ap')
                ->leftJoin($alias.'.gift', 'g')
                ->leftJoin($alias.'.specialPropositions', 'sp')
                ->leftJoin($alias.'.supply', 's')
                ->leftJoin($alias.'.orderDetail', 'od')
                ->leftJoin($alias.'.category', 'c');
        }
    }
}
