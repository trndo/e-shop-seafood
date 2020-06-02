<?php

namespace App\Repository;

use App\Collection\ReservationCollection;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @param int|null $id
     * @return Reservation[]|null
     */
    public function getReservationsById(?int $id): ?array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.uniqId = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->execute();
    }
}
