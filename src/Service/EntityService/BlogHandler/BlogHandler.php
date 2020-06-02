<?php


namespace App\Service\EntityService\BlogHandler;


use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;

class BlogHandler implements BlogHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var BlogRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, BlogRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }
    /**
     * @param Blog $blog
     * @return mixed
     */
    public function saveBlog(?Blog $blog): void
    {
        if ($blog !== null) {
            $this->entityManager->persist($blog);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Blog $blog
     * @return mixed
     */
    public function updateBlog(?Blog $blog): void
    {
        if ($blog !== null) {
            $this->entityManager->flush();
        }
    }

    public function getMessages(): ?array
    {
        return $this->repository->findAll();
    }

    public function deleteBlog(?Blog $blog): void
    {
        if ($blog !== null) {
            $this->entityManager->remove($blog);
            $this->entityManager->flush();
        }
    }


}