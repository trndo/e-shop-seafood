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
            ->select('p')
            ->andWhere('p.rating != 0')
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
        return $this->find(['slug' => $slug]);
    }
}
