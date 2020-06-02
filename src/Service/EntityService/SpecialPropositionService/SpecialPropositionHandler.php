<?php


namespace App\Service\EntityService\SpecialPropositionService;


use App\Collection\SpecialPropositionCollection;
use App\Repository\SpecialPropositionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SpecialPropositionHandler implements SpecialPropositionHandlerInterface
{
    /**
     * @var SpecialPropositionRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SpecialPropositionHandler constructor.
     * @param SpecialPropositionRepository $repository
     */
    public function __construct(SpecialPropositionRepository $repository,EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAllSpecialProposition(): SpecialPropositionCollection
    {
        return new SpecialPropositionCollection($this->repository->findAll());
    }

    public function removeSpecialProposition(?int $id): void
    {
        $sProposition =$this->repository->find($id);
        $this->entityManager->remove($sProposition);
        $this->entityManager->flush();
    }

    public function activateSpecialProposition(?int $id): bool
    {
        $sProposition =$this->repository->find($id);

        $sProposition->setStatus(!$sProposition->getStatus());

        $this->entityManager->flush();

        return $sProposition->getStatus();
    }
}