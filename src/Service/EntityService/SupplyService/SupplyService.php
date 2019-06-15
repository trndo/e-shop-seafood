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
            $supply->setQuantity($jsonData['quantity']);

            $this->entityManager->flush();
        }
    }

    public function getAllSupply(): SupplyCollection
    {
        return new SupplyCollection($this->getSupplyRepository()->findAll());
    }

    private function getSupplyRepository()
    {
        return $this->entityManager->getRepository(Supply::class);
    }
}