<?php


namespace App\Service\SearchService;


use App\Repository\RepositoryInterface\FinderInterface;

class Searcher implements SearcherInterface
{
    public function searchByName(string $name, FinderInterface $finder): ?array
    {
        if ($name) {
          return  $finder->findByName($name);
        }
        return [];
    }

    /**
     * @param string $name
     * @param FinderInterface $finder
     * @return array|null
     */
    public function searchByNameForRender(string $name, FinderInterface $finder): ?array
    {
        if ($name) {
            return $finder->findForRender($name);
        }
        return [];
    }
}