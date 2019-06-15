<?php


namespace App\Service\EntityService\SupplyService;


use App\Collection\SupplyCollection;

interface SupplyServiceInterface
{
    /**
     * @param array $jsonData
     */
    public function editSupply(array $jsonData): void ;

    /**
     * @return SupplyCollection
     */
    public function getAllSupply(): SupplyCollection ;
}