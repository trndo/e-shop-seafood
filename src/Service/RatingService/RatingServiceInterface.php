<?php

namespace App\Service\RatingService;

use Doctrine\ORM\EntityManagerInterface;

interface RatingServiceInterface
{
    /**
     * @param array $rating
     */
    public function updateRating(array $rating): void;

    /**
     * @param string|null $rate
     */
    public function removeFromRate(?string $rate): void;

    /**
     * @return array|null
     */
    public function getItems(): ?array ;
}