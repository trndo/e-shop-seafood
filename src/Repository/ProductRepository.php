<?php

namespace App\Repository;

use App\Entity\Product;
use App\Repository\RepositoryInterface\FinderInterface;
use App\Repository\RepositoryInterface\RatingInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            ->setParameter('productName', '%'.$productName.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findForRating(): ?array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.supply','supply')
            ->leftJoin('p.category','category')
            ->leftJoin('p.gift','gift')
            ->leftJoin('p.specialPropositions','specialPropositions')
            ->leftJoin('p.orderDetail','orderDetail')
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
            ->setParameter('productName', '%'.$productName.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findProductBySlug(string $slug):Product
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @param int $categoryId
     * @return array|null
     */
    public function getProductsFromCategory(int $categoryId): ?array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('c','od','ap','g','sp','s')
            ->leftJoin('p.additionalProduct','ap')
            ->leftJoin('p.gift','g')
            ->leftJoin('p.specialPropositions','sp')
            ->leftJoin('p.supply','s')
            ->leftJoin('p.orderDetail', 'od')
            ->leftJoin('p.category','c')
            ->andWhere('p.status = true AND c.id = :categoryId')
            ->setParameter('categoryId',$categoryId)
            ->getQuery()
            ->execute()
            ;
    }
}
