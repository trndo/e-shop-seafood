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
}