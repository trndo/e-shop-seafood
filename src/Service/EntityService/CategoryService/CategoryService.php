<?php


namespace App\Service\EntityService\CategoryService;


use App\Collection\CategoryCollection;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(CategoryRepository $repository,EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @return CategoryCollection
     */
    public function getAllCategories(): CategoryCollection
    {
        return new CategoryCollection($this->repository->findAll());
    }

    /**
     * @param $data
     */
    public function addCategory(Category $data): void
    {
        if ($data instanceof Category) {
           $this->repository->save($data);
        }
    }

    public function deleteCategory(Category $category): void
    {
        if ($category instanceof Category) {
            $this->repository->delete($category);
        }
    }
}