<?php

namespace App\Service\RatingService;

use Doctrine\ORM\EntityManagerInterface;

interface RatingServiceInterface
{
    /**
     * RatingServiceInterface constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager);

    /**
     * @param array $rating
     */
    public function updateRating(array $rating): void;

    /**
     * @param string|null $rate
     */
    public function removeFromRate(?string $rate): void;
}