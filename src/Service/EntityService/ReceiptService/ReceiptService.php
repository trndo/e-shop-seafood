<?php

namespace App\Service\EntityService\ReceiptService;

use App\Collection\CategoryCollection;
use App\Collection\ReceiptCollection;
use App\Entity\Category;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Model\ReceiptModel;
use App\Repository\CategoryRepository;
use App\Repository\ReceiptRepository;
use App\Service\FileSystemService\FileUploader;
use App\Service\FileSystemService\UploadFileInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReceiptService implements ReceiptServiceInterface
{
    private const RECEIPT_IMAGE_FOLDER = 'receipts/';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UploadFileInterface
     */
    private $fileUploader;

    /**
     * @var array $allowedQueryParams
     */
    private $allowedQueryParams = ['name','category'];

    /**
     * @var ReceiptRepository
     */
    private $receiptRepository;
    /**
     * @var CategoryRepository
     */
    private $repositoryCategory;

    public function __construct(EntityManagerInterface $entityManager,
                                FileUploader $fileUploader,
                                ReceiptRepository $receiptRepository,
                                CategoryRepository $repositoryCategory)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->receiptRepository = $receiptRepository;
        $this->repositoryCategory = $repositoryCategory;
    }

    public function saveReceipt(ReceiptModel $model): void
    {
        $receipt = $this->setNewReceipt($model);
        $this->entityManager->persist($receipt);
        $this->entityManager->flush();

        $this->uploadReceiptPhotos($model->getPhoto(), $receipt);
    }

    public function setNewReceipt(ReceiptModel $model): Receipt
    {
        $receipt = new Receipt();
        $receipt = $this->setReceiptFromModel($receipt, $model);

        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(),self::RECEIPT_IMAGE_FOLDER);
            $receipt->setTitlePhoto($newTitlePhoto);
        }

        return  $receipt;
    }

    public function updateReceipt(Receipt $receipt, ReceiptModel $model): void
    {
        $receipt = $this->setReceiptFromModel($receipt, $model);
        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(),self::RECEIPT_IMAGE_FOLDER, $receipt->getTitlePhoto());
            $receipt->setTitlePhoto($newTitlePhoto);
        }
        $this->entityManager->flush();
    }

    public function deleteReceipt(Receipt $receipt): void
    {
        $receipt->setIsDeletable(true)
                        ->setStatus(false);

        $this->entityManager->flush();
    }

    public function activateReceipt(?int $id): void
    {
        $receipt = $this->receiptRepository->find($id);

        if($receipt){
            if($receipt->getStatus())
                $receipt->setStatus(false);
            else
                $receipt->setStatus(true);

            $this->entityManager->flush();
        }
    }

    /**
     * @return ReceiptCollection
     */
    public function getReceipts(): ReceiptCollection
    {
        return new ReceiptCollection($this->receiptRepository->findAll());
    }

    /**
     * @param string $name
     * @param int $category
     * @param bool $includeStatus
     * @return ReceiptCollection
     */
    public function getReceiptsByCriteria(?string $name, ?int $category, bool $includeStatus = false): ?ReceiptCollection
    {
        return new ReceiptCollection($this->receiptRepository->findReceiptsBy($name, $category, $includeStatus));
    }

    /**
     * @param array $products
     * @param Receipt|null $receipt
     */
    public function addProductsInReceipt(array $products, ?Receipt $receipt): void
    {
        $productRepo = $this->entityManager->getRepository(Product::class);

        foreach ($receipt->getProducts() as $existingProduct){
            if(!in_array($existingProduct->getId(),$products))
                $receipt->removeProduct($existingProduct);
        }

        foreach ($products as $product) {
           $product =  $productRepo->find($product);
           $receipt->addProduct($product);
        }

        $this->entityManager->flush();
    }

    public function addSalesInReceipt(array $products,?Receipt $receipt): void
    {
        $productRepo = $this->entityManager->getRepository(Product::class);
        $receiptRepo = $this->entityManager->getRepository(Receipt::class);

        foreach ($receipt->getProductSalesFromReceipt() as $existingProductSale){
            if(!$this->inArrayByCallback($existingProductSale->getId(),$existingProductSale->getType(),$products))
                $receipt->removeProductSalesFromReceipt($existingProductSale);
        }
        foreach ($receipt->getAdditionalReceipts() as $existingReceipt) {
            if(!$this->inArrayByCallback($existingReceipt->getId(),$existingReceipt->getType(),$products))
                $receipt->removeAdditionalReceipt($existingReceipt);
        }

        foreach ($products as $product) {
            switch ($product['type']) {
                case 'product':
                    $product = $productRepo->find($product['id']);
                    $receipt->addProductSalesFromReceipt($product);
                    continue;

                case 'receipt':
                    $receiptAdd = $receiptRepo->find($product['id']);
                    $receipt->addAdditionalReceipt($receiptAdd);
                    continue;

                default:
                    continue;
            }
        }
        $this->entityManager->flush();
    }

    private function inArrayByCallback(int $id,string $type, array $searchedArray){
        return current(array_filter($searchedArray, function($element) use($id, $type) {
            return $element['id'] == $id && $element['type'] == $type;
        }));
    }

    public function getReceiptsForRating(): ?array
    {
        return $this->receiptRepository->findForRating();
    }

    /**
     * Get receipt
     *
     * @param string|null $slug
     * @return Receipt
     */
    public function getReceipt(?string $slug): Receipt
    {
       return $this->receiptRepository->findReceiptBySlug($slug);
    }

    public function getReceiptsByCategory(Category $category, bool $setMaxResults = false): ?ReceiptCollection
    {
        return new ReceiptCollection($this->receiptRepository->getReceiptsFromCategory($category->getId(),$setMaxResults));
    }

    public function loadMoreReceipts(Category $category, int $count): ?ReceiptCollection
    {
        if ($category && $count !== null) {
            return new ReceiptCollection(
                $this->receiptRepository->getReceiptsForLoading($category->getId(), $count)
            );
        }

        return null;
    }

    public function getReceiptsCategories(): ?CategoryCollection
    {
        return new CategoryCollection($this->repositoryCategory->getCategories('receipt'));
    }

    public function getReceiptById(?int $receiptId): ?Receipt
    {
        return $this->receiptRepository->findById($receiptId);
    }

    private function uploadReceiptPhotos(array $photos,Receipt $receipt): void
    {
        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $receiptPhoto = new Photo();
                $newPhoto = $this->upload($photo,self::RECEIPT_IMAGE_FOLDER);
                $receiptPhoto->setHash($newPhoto)
                    ->setReceipt($receipt);

                $this->entityManager->persist($receiptPhoto);
                $this->entityManager->flush();
            }
        }
    }

    private function hydrateQuery(array $query): array
    {
        foreach ($query as $key => $param){
            if(!in_array($key,$this->allowedQueryParams))
                unset($query[$key]);
        }

        return $query;
    }

    private function upload(?UploadedFile $file, string $folder, $hash = null): ?string
    {
        return $this->fileUploader->uploadFile($file, $folder, $hash);
    }

    private function setReceiptFromModel(Receipt $receipt, ReceiptModel $model): Receipt
    {
        $price = 0;
        $receipt->setName($model->getName())
            ->setCategory($model->getCategory())
            ->setDescription($model->getDescription())
            ->setPrice($model->getPrice())
            ->setSeoDescription($model->getSeoDescription())
            ->setSeoTitle($model->getSeoTitle())
            ->setUnit($model->getUnit())
            ->setPercent($model->getPercent())
            ->setAdditionalPrice($model->getAdditionalPrice());

        $model->getAdditionalPrice() ? $price += $model->getAdditionalPrice() + $model->getPrice() : $price += $model->getPrice();
        $model->getPercent() ? $price += $price * $model->getPercent() : $price += $price;

        $receipt->setPrice(ceil($price));
        return $receipt;
    }


}