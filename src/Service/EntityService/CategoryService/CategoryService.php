<?php


namespace App\Service\EntityService\CategoryService;


use App\Collection\CategoryCollection;
use App\Entity\Category;
use App\Mapper\CategoryMapper;
use App\Model\CategoryModel;
use App\Repository\CategoryRepository;
use App\Service\FileSystemService\UploadFileInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryService implements CategoryServiceInterface
{
    private const CATEGORY_IMAGE_FOLDER = 'categories/';
    /**
     * @var CategoryRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UploadFileInterface
     */
    private $uploadFile;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $repository
     * @param EntityManagerInterface $em
     * @param UploadFileInterface $uploadFile
     */
    public function __construct(CategoryRepository $repository,EntityManagerInterface $em, UploadFileInterface $uploadFile)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->uploadFile = $uploadFile;
    }

    /**
     * @return CategoryCollection
     */
    public function getAllCategories(): CategoryCollection
    {
        return new CategoryCollection($this->repository->findAll());
    }

    /**
     * @param CategoryModel $categoryModel
     */
    public function addCategory(CategoryModel $categoryModel): void
    {
        $category = new Category();
        CategoryMapper::modelToEntity($categoryModel, $category);
        $modelTitlePhoto = $categoryModel->getTitlePhoto();

        if ($modelTitlePhoto instanceof UploadedFile) {
            $titlePhoto = $this->uploadFile->uploadFile($modelTitlePhoto, self::CATEGORY_IMAGE_FOLDER);
            $category->setTitlePhoto($titlePhoto);
        }

        $this->repository->save($category);
    }

    public function deleteCategory(Category $category): void
    {
        if ($category instanceof Category) {
            $this->repository->delete($category);
        }
    }

    public function getCategoryByCriteria(array $criteria, array $orderBy = []): ?CategoryCollection
    {
        return new CategoryCollection($this->repository->findBy($criteria,$orderBy));
    }

    public function getCategoryForHeader(): ?CategoryCollection
    {
        return new CategoryCollection($this->repository->getCategoriesForRender());
    }

    public function updateCategory(Category $category, CategoryModel $categoryModel): void
    {
        $category = CategoryMapper::modelToEntity($categoryModel,$category);
        $modelTitlePhoto = $categoryModel->getTitlePhoto();

        if ($modelTitlePhoto instanceof UploadedFile) {
            $titlePhoto = $this->uploadFile->uploadFile($modelTitlePhoto, self::CATEGORY_IMAGE_FOLDER,$category->getTitlePhoto());
            $category->setTitlePhoto($titlePhoto);
        }

        $this->em->flush();
    }

    public function getCategoriesByType(?string $type): ?CategoryCollection
    {
        return new CategoryCollection(
            $this->repository->getCategories($type)
        );
    }

    public function getCategoryById(?int $id): ?Category
    {
        if ($id) {
           return $this->repository->getCategoryById($id);
        }

        return null;
    }

    public function changeStatus(Category $category): bool
    {
       $status = $category->getStatus() ? false : true ;
       $category->setStatus($status);
       $this->em->flush();

       return $status;
    }
}