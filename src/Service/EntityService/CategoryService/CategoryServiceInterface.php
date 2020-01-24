<?php


namespace App\Service\EntityService\CategoryService;


use App\Collection\CategoryCollection;
use App\Entity\Category;
use App\Model\CategoryModel;
use App\Service\FileSystemService\UploadFileInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface CategoryServiceInterface
{
    /**
     * @return CategoryCollection
     *
     * Get all Categories
     */
    public function getAllCategories(): CategoryCollection;

    /**
     * Add CategoryInfo in DB
     * @param CategoryModel $categoryModel
     */
    public function addCategory(CategoryModel $categoryModel): void ;

    /**
     * @param Category $category
     *
     * Delete Category
     */
    public function deleteCategory(Category $category);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return CategoryCollection|null
     */
    public function getCategoryByCriteria(array $criteria, array $orderBy = []): ?CategoryCollection;

    /**
     * @return CategoryCollection|null
     */
    public function getCategoryForHeader(): ?CategoryCollection;

    /**
     * @param Category $category
     * @param CategoryModel $categoryModel
     */
    public function updateCategory(Category $category, CategoryModel $categoryModel): void ;

    /**
     * @param string|null $type
     * @return CategoryCollection|null
     */
    public function getCategoriesByType(?string $type): ?CategoryCollection;

    /**
     * @param int|null $id
     * @return Category
     */
    public function getCategoryById(?int $id): ?Category;

    /**
     * @param Category $category
     * @return bool
     */
    public function changeStatus(Category $category): bool ;



}