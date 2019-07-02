<?php


namespace App\Service\EntityService\SpecialPropositionService;


use App\Collection\SpecialPropositionCollection;
use App\Repository\SpecialPropositionRepository;

class SpecialPropositionHandler implements SpecialPropositionHandlerInterface
{
    /**
     * @var SpecialPropositionRepository
     */
    private $repository;

    /**
     * SpecialPropositionHandler constructor.
     * @param SpecialPropositionRepository $repository
     */
    public function __construct(SpecialPropositionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllSpecialProposition(): SpecialPropositionCollection
    {
        return new SpecialPropositionCollection($this->repository->findAll());
    }
}