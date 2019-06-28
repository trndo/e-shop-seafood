<?php

namespace App\Repository;

use App\Entity\Receipt;
use App\Repository\RepositoryInterface\FinderInterface;
use App\Repository\RepositoryInterface\RatingInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            ->andWhere('r.name LIKE :receiptName')
            ->setParameter('receiptName', '%'.$receiptName.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findForRating(): ?array
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->andWhere('r.rating != 0')
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
            ->andWhere('r.name LIKE :productName ')
            ->setParameter('productName', '%'.$productName.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findReceiptBySlug(string $slug):Receipt
    {
        return $this->find(['slug' => $slug]);
    }
}
