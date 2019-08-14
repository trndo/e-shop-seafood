<?php


namespace App\Service\EntityService\ProductService;

use App\Collection\ProductCollection;
use App\Entity\Category;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Supply;
use App\Model\ProductModel;
use App\Repository\ProductRepository;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\FileSystemService\UploadFileInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductService implements ProductServiceInterface
{
    private const PRODUCT_IMAGE_FOLDER = 'products/';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UploadFileInterface
     */
    private $fileUploader;

    /**
     * @var array $allowedGetParameters
     */
    private $allowedGetParameters = ['name', 'category'];

    /**
     * @var ProductRepository $productRepository
     */
    private $productRepository;

    public function __construct(EntityManagerInterface $entityManager,
                                UploadFileInterface $fileUploader,
                                ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->productRepository = $productRepository;
    }

    public function saveProduct(ProductModel $model): void
    {
        $product = $this->setNewProduct($model);
        $this->entityManager->persist($product);

        $this->uploadProductPhotos($model->getPhoto(), $product);

        $supply = $this->setSupply($product);
        $this->entityManager->persist($supply);

        $this->entityManager->flush();

    }

    /**
     * @param UploadedFile|null $file
     * @param string $folder
     * @param null $hash
     * @return string|null
     */
    private function upload(?UploadedFile $file, string $folder, $hash = null): ?string
    {
        return $this->fileUploader->uploadFile($file, $folder, $hash);
    }

    private function uploadProductPhotos(array $photos, Product $product): void
    {
        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $productPhoto = new Photo();
                $newPhoto = $this->upload($photo, self::PRODUCT_IMAGE_FOLDER);
                $productPhoto->setHash($newPhoto)
                    ->setProduct($product);

                $this->entityManager->persist($productPhoto);
                $this->entityManager->flush();
            }
        }
    }

    private function setNewProduct(ProductModel $model): Product
    {
        $product = new Product();

        $product->setName($model->getName())
            ->setUnit($model->getUnit())
            ->setPrice($model->getPrice())
            ->setDescription($model->getDescription())
            ->setSeoDescription($model->getSeoDescription())
            ->setSeoTitle($model->getSeoTitle())
            ->setProductSize($model->getProductSize())
            ->setAmountPerUnit($model->getAmountPerUnit())
            ->setWeightPerUnit($model->getWeightPerUnit())
            ->setCategory($model->getCategory());

        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(), self::PRODUCT_IMAGE_FOLDER);
            $product->setTitlePhoto($newTitlePhoto);
        }

        return $product;
    }

    private function setSupply(Product $product): Supply
    {
        $supply = new Supply();

        $supply->setQuantity(0)
            ->setReservationQuantity(0)
            ->setProduct($product);

        return $supply;
    }

    /**
     * @return ProductCollection
     */
    public function getProducts(): ProductCollection
    {
        return new ProductCollection($this->productRepository->findAll());
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return ProductCollection
     */
    public function getProductsByCriteria(array $criteria, array $orderBy = []): ProductCollection
    {
        return new ProductCollection($this->productRepository->findBy($this->hydrateQuery($criteria), $orderBy));
    }

    public function updateProduct(Product $product, ProductModel $model): void
    {
        $product->setName($model->getName())
            ->setUnit($model->getUnit())
            ->setPrice($model->getPrice())
            ->setDescription($model->getDescription())
            ->setSeoDescription($model->getSeoDescription())
            ->setSeoTitle($model->getSeoTitle())
            ->setProductSize($model->getProductSize())
            ->setAmountPerUnit($model->getAmountPerUnit())
            ->setWeightPerUnit($model->getWeightPerUnit())
            ->setCategory($model->getCategory());

        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(), self::PRODUCT_IMAGE_FOLDER, $product->getTitlePhoto());
            $product->setTitlePhoto($newTitlePhoto);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Product $product
     */
    public function deleteProduct(Product $product): void
    {
        $titlePhoto = $this->fileUploader->getUploadDir() . self::PRODUCT_IMAGE_FOLDER . $product->getTitlePhoto();
        if (file_exists($titlePhoto) && is_file($titlePhoto))
            unlink($titlePhoto);

        foreach ($product->getPhotos() as $photo) {
            $photoHash = $this->fileUploader->getUploadDir() . self::PRODUCT_IMAGE_FOLDER . $photo->getHash();
            if (file_exists($photoHash) && is_file($photoHash))
                unlink($photoHash);
            $this->entityManager->remove($photo);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    /**
     * @param int|null $id
     */
    public function activateProduct(?int $id): void
    {
        $product = $this->productRepository->find($id);

        if ($product) {
            if ($product->getStatus())
                $product->setStatus(false);
            else
                $product->setStatus(true);

            $this->entityManager->flush();
        }
    }

    private function hydrateQuery(array $query): array
    {
        foreach ($query as $key => $param) {
            if (!in_array($key, $this->allowedGetParameters))
                unset($query[$key]);
        }

        return $query;
    }

    /**
     * @return array|null
     */
    public function getProductsForRating(): ?array
    {
        return $this->productRepository->findForRating();
    }

    /**
     * @param string|null $slug
     * @return Product
     */
    public function getProduct(?string $slug): Product
    {
        return $this->productRepository->findProductBySlug($slug);
    }

    public function getProductById(?int $id): Product
    {
        return $this->productRepository->find($id);
    }

    public function getProductsByCategory(Category $category): ?array
    {
        return $this->productRepository->getProductsFromCategory($category->getId());
    }

    public function loadMoreProducts(Category $category, int $count): ?ProductCollection
    {
        if ($category && $count !== null) {
            return new ProductCollection(
                $this->productRepository->getProductsForLoading($category->getId(), $count)
            );
        }

        return null;
    }


}