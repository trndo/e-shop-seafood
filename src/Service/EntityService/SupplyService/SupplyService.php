<?php


namespace App\Service\EntityService\SupplyService;

use App\Collection\SupplyCollection;
use App\Entity\Supply;
use App\Repository\SupplyRepository;
use Doctrine\ORM\EntityManagerInterface;

class SupplyService implements SupplyServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SupplyRepository
     */
    private $supplyRepository;

    /**
     * SupplyService constructor.
     * @param EntityManagerInterface $entityManager
     * @param SupplyRepository $supplyRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SupplyRepository $supplyRepository)
    {
        $this->entityManager = $entityManager;
        $this->supplyRepository = $supplyRepository;
    }

    public function editSupply(array $jsonData): void
    {
        $supply = $this->supplyRepository->find($jsonData['id']);

        if (isset($supply)) {
            $newQuantity = $jsonData['quantity'];
            $quantity = $supply->getQuantity();

            $this->setNewQuantity($newQuantity, $quantity, $supply);

            $this->entityManager->flush();
        }
    }

    public function getAllSupply(): ?SupplyCollection
    {
        return new SupplyCollection($this->supplyRepository->findAllSupplies());
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

    public function findByCriteria(?int $category): ?SupplyCollection
    {
        return new SupplyCollection($this->supplyRepository->findSuppliesBy($category));
    }



}