<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Category::class);
        $this->entityManager = $entityManager;
    }

    public function save(Category $category): void
    {
        if ($category instanceof Category) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        }
    }

    public function delete(Category $category): void
    {
        if ($category instanceof Category) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        }
    }

    public function getProductsByCategory($categoryId): ?array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.products','p')
            ->addSelect('p')
            ->andWhere('p.category = :categoryId')
            ->setParameter('categoryId',$categoryId)
            ->getQuery()
            ->getResult();
    }

    public function getCategories(string $item): ?array
    {
         $query = $this->createQueryBuilder('c')
             ->addSelect('c');
            $item == 'product'
            ? $query->andWhere('c.type = :products')
                ->setParameter('products','products')
            : $query->andWhere('c.type = :receipts')
                ->setParameter('receipts','receipts');

            return $query->orderBy('c.id','ASC')
                ->getQuery()
                ->getResult();
    }

    public function getCategoriesForRender(): ?array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name != :name1')
            ->andWhere('c.name != :name2')
            ->andWhere('c.name != :name3')
            ->andWhere('c.status = true')
            ->setParameter('name1','Живые Раки')
            ->setParameter('name2','Жареные Раки')
            ->setParameter('name3','Вареные Раки')
            ->getQuery()
            ->getResult();
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getOneOrNullResult();
    }



}
