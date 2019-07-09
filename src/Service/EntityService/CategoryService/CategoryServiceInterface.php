<?php


namespace App\Service\EntityService\CategoryService;


use App\Collection\CategoryCollection;
use App\Entity\Category;

interface CategoryServiceInterface
{
    /**
     * @return CategoryCollection
     *
     * Get all Categories
     */
    public function getAllCategories(): CategoryCollection;

    /**
     * @param Category $data
     *
     * Add Category in DB
     */
    public function addCategory(Category $data);

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
}