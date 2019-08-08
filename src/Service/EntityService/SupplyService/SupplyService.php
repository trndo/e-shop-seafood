<?php


namespace App\Service\EntityService\SupplyService;

use App\Collection\SupplyCollection;
use App\Entity\Supply;
use Doctrine\ORM\EntityManagerInterface;

class SupplyService implements SupplyServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SupplyService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function editSupply(array $jsonData): void
    {
        $repository = $this->getSupplyRepository();
        $supply = $repository->find($jsonData['id']);

        if (isset($supply)) {
            $newQuantity = $jsonData['quantity'];
            $quantity = $supply->getQuantity();

            $this->setNewQuantity($newQuantity, $quantity, $supply);

            $this->entityManager->flush();
        }
    }

    public function getAllSupply(): SupplyCollection
    {
        return new SupplyCollection($this->getSupplyRepository()->findAll());
    }

    public function setNewQuantity(float $newQuantity, float $quantity, Supply $supply): void
    {
        $reservationQuantity = $supply->getReservationQuantity();

        if ($quantity <= $newQuantity) {
            $diff = $newQuantity - $quantity;
            $supply->setQuantity($quantity + $diff);
            $supply->setReservationQuantity($reservationQuantity + $diff);
        }
        if ($quantity >= $newQuantity) {
            $diff = $quantity - $newQuantity;
            $supply->setQuantity($quantity - $diff);
            $supply->setReservationQuantity($reservationQuantity - $diff);
        }
    }

    private function getSupplyRepository()
    {
        return $this->entityManager->getRepository(Supply::class);
    }
}