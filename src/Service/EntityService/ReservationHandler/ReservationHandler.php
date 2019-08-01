<?php


namespace App\Service\EntityService\ReservationHandler;


use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationHandler implements ReservationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * ReservationHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ReservationRepository $reservationRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository)
    {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
    }


    /**
     * @param string $productId
     * @param bool $orderType
     * @param float $quantity
     * @return mixed
     */
    public function reserve(string $productId, bool $orderType, float $quantity): void
    {
        $explodedId = $this->explodeProductId($productId);
        $reservation = $this->reservationRepository->findOneBy(['product' => $productId]);

        if ($reservation instanceof Reservation) {
        }
    }

    /**
     * @param string $productId
     * @return Reservation
     */
    public function getReservation(string $productId): Reservation
    {
        // TODO: Implement getReservation() method.
    }

    /**
     * @param string $productId
     * @return mixed
     */
    public function deleteReservation(string $productId): void
    {
        // TODO: Implement deleteReservation() method.
    }

    private function explodeProductId(string $productId)
    {
        return (int)explode('-',$productId)[0];
    }

    private function updateSupply(float $newQuantity, float $oldQuantity): float
    {
        $difference = 0;
        if ($newQuantity > $oldQuantity) {
            $difference = ($newQuantity - $oldQuantity)*-1;
        }

        if ($newQuantity < $oldQuantity) {
            $difference = $newQuantity - $oldQuantity;
        }

        return $difference;
    }
}