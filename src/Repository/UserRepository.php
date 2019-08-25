<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[]
     */
    public function findAdmins(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles','%ROLE_AD%')
            ->getQuery()
            ->execute();
    }

    public function findUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles NOT LIKE :roles')
            ->setParameter('roles','%ROLE_AD%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findTokens(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.token')
            ->getQuery()
            ->execute();
    }

    public function delete(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    public function save(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @param string|null $uniqueId
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUserByUniqueId(?string $uniqueId): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.uniqueId = :uniqueId')
            ->setParameter('uniqueId',$uniqueId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
