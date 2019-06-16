<?php


namespace App\Repository\RepositoryInterface;


interface FinderInterface
{
    /**
     * Find name of products and return array of names
     *
     * @param string $name
     * @return array|null
     */
    public function findByName(string $name): ?array ;
}