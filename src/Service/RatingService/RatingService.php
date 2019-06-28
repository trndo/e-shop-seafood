<?php


namespace App\Service\RatingService;


use App\Entity\Product;
use App\Entity\Receipt;
use Doctrine\ORM\EntityManagerInterface;

class RatingService implements RatingServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RatingService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param array $rating
     */
    public function updateRating(array $rating): void
    {
        foreach ($rating as $position => $rate) {
           $rate = explode('_',$rate);
           if(!isset($rate[1]))
               return;
           $repo = $rate[0] == 'product' ?  $this->em->getRepository(Product::class) : $this->em->getRepository(Receipt::class);
           $good = $repo->find($rate[1]);
           $good->setRating($position + 1);
           $this->em->flush();
        }
    }

    /**
     * @param string|null $rate
     */
    public function removeFromRate(?string $rate): void
    {
        if(isset($rate)){
            $rate = explode('_',$rate);
            $repo = $rate[0] == 'product' ?  $this->em->getRepository(Product::class) : $this->em->getRepository(Receipt::class);
            $good = $repo->find($rate[1]);
            $good->setRating(0);
            $this->em->flush();
        }
    }

}