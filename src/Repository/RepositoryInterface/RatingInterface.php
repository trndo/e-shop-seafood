<?php

namespace App\Repository\RepositoryInterface;
/**
 * Interface RatingInterface
 * @package App\Repository\RepositoryInterface
 */
interface RatingInterface
{
    /**
     * @return array|null
     */
    public function findForRating(): ?array;
}