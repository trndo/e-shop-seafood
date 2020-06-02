<?php


namespace App\Service\EntityService\SpecialPropositionService;


use App\Collection\SpecialPropositionCollection;

interface SpecialPropositionHandlerInterface
{
    /**
     * Return collection of specialPropositions
     *
     * @return SpecialPropositionCollection
     */
    public function getAllSpecialProposition(): SpecialPropositionCollection ;

    /**
     * Delete special proposition by id
     *
     * @param int $id
     */
    public function removeSpecialProposition(?int $id): void ;

    /**
     * Activate special proposition
     *
     * @param int|null $id
     * @return mixed
     */
    public function activateSpecialProposition(?int $id): bool ;
}