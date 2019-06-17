<?php


namespace App\Service\SearchService;

use App\Repository\RepositoryInterface\FinderInterface;

interface SearcherInterface
{
    /**
     * Find product name by name
     *
     * @param string $name
     * @param FinderInterface $finder
     * @return array|null
     */
    public function searchByName(string $name, FinderInterface $finder): ?array ;

    /**
     * @param string $name
     * @param FinderInterface $finder
     * @return array|null
     */
    public function searchByNameForRender(string $name, FinderInterface $finder): ?array ;
}