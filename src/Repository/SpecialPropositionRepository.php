<?php

namespace App\Repository;

use App\Entity\SpecialProposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpecialProposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialProposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialProposition[]    findAll()
 * @method SpecialProposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialPropositionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpecialProposition::class);
    }
}
