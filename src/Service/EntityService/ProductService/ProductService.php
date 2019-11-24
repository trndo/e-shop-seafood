<?php


namespace App\Service\EntityService\ProductService;

use App\Collection\CategoryCollection;
use App\Collection\ProductCollection;
use App\Entity\Category;
use App\Entity\OrderDetail;
use App\Entity\OrderInfo;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\Supply;
use App\Model\ProductModel;
use App\Repository\CategoryRepository;
use App\Repository\OrderInfoRepository;
use App\Repository\ProductRepository;
use App\Service\EntityService\CategoryService\CategoryServiceInterface;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use App\Service\FileSystemService\UploadFileInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * @var CategoryServiceInterface
     */
    private $categoryService;

    /**
     * @var OrderInfoRepository
     */
    private $infoRepository;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager,
                                UploadFileInterface $fileUploader,
                                ProductRepository $productRepository,
                                CategoryRepository $categoryService,
                                OrderInfoRepository $infoRepository,
                                UrlGeneratorInterface $urlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->productRepository = $productRepository;
        $this->categoryService = $categoryService;
        $this->infoRepository = $infoRepository;
        $this->urlGenerator = $urlGenerator;
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

    private function setSupply(Product $product): Supply
    {
        $supply = new Supply();

        $supply->setQuantity(0)
            ->setReservationQuantity(0)
            ->setProduct($product);

        return $supply;
    }

    public function getProducts(): ProductCollection
    {
        return new ProductCollection($this->productRepository->findAll());
    }

    function getProductsByCriteria(?string $name, ?int $category): ?ProductCollection
    {
        return new ProductCollection($this->productRepository->findProductsBy($name, $category));
    }

    public function updateProduct(Product $product, ProductModel $model): void
    {
        $product = $this->setProductFromModel($product, $model);
        if ($product->getProductSize() !== $model->getProductSize()) {
            $product->setProductSize($model->getProductSize());
        }

        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(), self::PRODUCT_IMAGE_FOLDER, $product->getTitlePhoto());
            $product->setTitlePhoto($newTitlePhoto);
        }

        $this->entityManager->flush();
    }

    public function deleteProduct(Product $product): void
    {
        $product->setIsDeletable(true)
                ->setStatus(false)
                ->getSupply()->setQuantity(0)
                             ->setReservationQuantity(0);

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

    public function getProductsForRating(): ?array
    {
        return $this->productRepository->findForRating();
    }

    public function getProduct(?string $slug): Product
    {
        return $this->productRepository->findProductBySlug($slug);
    }

    public function getProductById(?int $id): Product
    {
        return $this->productRepository->find($id);
    }

    public function getProductsByCategory(Category $category, bool $setMaxResults = false): ?array
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

    public function getProductsCategories(): ?CategoryCollection
    {
        return new CategoryCollection(
            $this->categoryService->getCategories('product')
        );
    }

    public function adjustmentAddingProduct(?Product $product, ?int $orderId, ?Receipt $receipt): array
    {
        $order = $this->infoRepository->getOrderById($orderId);
        if ($product && $order) {
            $productSupply = $product->getSupply();
            if ($productSupply->getReservationQuantity() <= 0) {
                return [
                    'status' => false,
                    'message' => 'Недостаточное количество резерва. Осталось - '.$productSupply->getReservationQuantity()
                ];
            } else {
                if ($this->checkIsExistedOrderDetail($order->getOrderDetails(), $receipt, $product)) {
                    return [
                        'status' => false,
                        'message' => 'Такой товар уже сущевствует в заказе! Изменяйте его в разделе "Изменить заказ"!'
                    ];
                }
                $orderDetail = $this->setNewOrderDetail($productSupply, $product, $receipt, $order);
                $this->entityManager->persist($orderDetail);
                $this->entityManager->flush();

                return [
                    'status' => true,
                    'order' => $this->urlGenerator->generate(
                        'editOrder',['id' => $order->getId()],UrlGeneratorInterface::ABSOLUTE_URL
                    )];
            }
        }
        return [
            'status' => false,
            'message' => 'Такого заказа не сущевствует!'
        ];
    }

    public function getSizes(int $id, string $orderType): ProductCollection
    {
        switch ($orderType) {
            case 'today':
                return new ProductCollection($this->productRepository->findAllAvailableSizes($id));
            case 'tomorrow':
                return new ProductCollection($this->productRepository->findAllSizes($id));
            default:
                return new ProductCollection([]);
        }
    }

    public function adjustmentProductQuantity(?Product $product, ?Receipt $receipt, ?float $value, ?int $orderId): ?array
    {
        $order = $this->infoRepository->getOrderById($orderId);
        $recountQuantity = 0;

        if ($product && $order) {
            $productSupply = $product->getSupply();
            $reservations = $productSupply->getReservationQuantity();

            $orderDetail = $this->checkIsExistedOrderDetail($order->getOrderDetails(), $receipt, $product);
            if ($orderDetail) {
                $oldQuantity = $orderDetail->getQuantity();
                   $value > $oldQuantity ? $recountQuantity = $value - $oldQuantity : $recountQuantity = $oldQuantity - $value;

                    if ($recountQuantity <= $reservations || $this->checkIsCurrentDate($order->getOrderDate())) {

                    $oldPrice = $this->getOrderDetailPrice($orderDetail, $oldQuantity);
                    $newPrice = $this->getOrderDetailPrice($orderDetail, $value);

                    $priceDiff = $this->recognizeDiff($newPrice,$oldPrice);
                    $reservationDiff = $this->recognizeDiff($value, $oldQuantity);

                    $order->setTotalPrice($order->getTotalPrice() - $priceDiff);

                    if ($this->checkIsCurrentDate($order->getOrderDate())) {
                        $productSupply->setReservationQuantity($productSupply->getReservationQuantity() + $reservationDiff);
                    }

                    $orderDetail->setQuantity($value);

                    $this->entityManager->flush();

                    return [
                      'status' => true,
                      'totalSum' => $order->getTotalPrice(),
                      'reservation' => $productSupply->getReservationQuantity()
                    ];
                }
                return [
                    'status' => false,
                    'message' => 'Недостаточное количество на складе',
                    'totalSum' => $order->getTotalPrice(),
                ];
            }
            return [
                'status' => false,
                'message' => 'Такого товара не сущевствует!',
                'totalSum' => $order->getTotalPrice()
            ];
        }
        return null;
    }

    public function addSalesInProduct(array $products, ?Product $product): void
    {
        $productRepo = $this->entityManager->getRepository(Product::class);
        $receiptRepo = $this->entityManager->getRepository(Receipt::class);

        foreach ($product->getReceiptSales() as $existingReceiptSale){
            if(!$this->inArrayByCallback($existingReceiptSale->getId(), $existingReceiptSale->getType(), $products))
                $product->removeReceiptSales($existingReceiptSale);
        }
        foreach ($product->getProducts() as $existingProduct) {
            if(!$this->inArrayByCallback($existingProduct->getId(), $existingProduct->getType(), $products))
                $product->removeProduct($existingProduct);
        }

        foreach ($products as $productPosition) {
            switch ($productPosition['type']) {
                case 'receipt':
                    $receipt = $receiptRepo->find($productPosition['id']);
                    $product->addReceiptSale($receipt);
                    continue;

                case 'product':
                    $productAdd = $productRepo->find($productPosition['id']);
                    $product->addProduct($productAdd);
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

    private function getOrderDetailPrice(OrderDetail $orderDetail, $value): float
    {
        $price = 0;
        $orderDetailProduct = $orderDetail->getProduct();
        $orderDetailReceipt = $orderDetail->getReceipt();

        $orderDetailReceipt
            ? $price += ceil($value) * $orderDetailReceipt->getPrice() + $value * $orderDetailProduct->getPrice()
            : $price += $value * $orderDetailProduct->getPrice();

        return $price;
    }

    private function setNewOrderDetail(Supply $productSupply, Product $product, ?Receipt $receipt, OrderInfo $order): OrderDetail
    {
        $orderDetail = new OrderDetail();
        $value = 1;
        $amount = 0;

        $diff = $productSupply->getReservationQuantity() - $value;

        $receipt !== null
            ? $orderDetail->setReceipt($receipt)->setProduct($product)
            : $orderDetail->setProduct($product);

        $orderDetail->setQuantity($value);
        $order->addOrderDetail($orderDetail);

        $orderDetail->getReceipt()
            ? $amount += ceil($value) * $orderDetail->getReceipt()->getPrice() + $value * $orderDetail->getProduct()->getPrice()
            : $amount += $value * $product->getPrice();

        $order->setTotalPrice($order->getTotalPrice() + $amount);

        if ($this->checkIsCurrentDate($order->getOrderDate())) {
            $productSupply->setReservationQuantity($diff);
        }

        return $orderDetail;
    }

    private function recognizeDiff(float $newQuantity, float $oldQuantity): float
    {
        $difference = 0;
        if ($newQuantity > $oldQuantity) {
            $difference = ($newQuantity - $oldQuantity) * -1;
        }

        if ($newQuantity < $oldQuantity) {
            $difference = $oldQuantity - $newQuantity;
        }

        return $difference;
    }

    private function checkIsExistedOrderDetail(Collection $orderDetails, ?Receipt $receipt, ?Product $product): ?OrderDetail
    {
        foreach ($orderDetails as $orderDetail) {
                /** @var OrderDetail $orderDetail */
                $orderDetailProduct = $orderDetail->getProduct();
                $orderDetailReceipt = $orderDetail->getReceipt();

                if ($receipt === null && $orderDetailReceipt === null){
                    if ($orderDetailProduct->getId() === $product->getId()){
                        return $orderDetail;
                    }
                }
                elseif ($receipt instanceof Receipt && $orderDetailReceipt instanceof Receipt) {
                    if($orderDetailReceipt->getId() == $receipt->getId() && $orderDetailProduct->getId() === $product->getId()) {
                        return $orderDetail;
                    }
                }
        }
        return null;
    }

    private function setProductFromModel(Product $product, ProductModel $model): Product
    {
        $price = 0;
        $product->setName($model->getName())
            ->setUnit($model->getUnit())
            ->setDescription($model->getDescription())
            ->setSeoDescription($model->getSeoDescription())
            ->setSeoTitle($model->getSeoTitle())
            ->setAmountPerUnit($model->getAmountPerUnit())
            ->setWeightPerUnit($model->getWeightPerUnit())
            ->setCategory($model->getCategory())
            ->setPercent($model->getPercent())
            ->setAdditionPrice($model->getAdditionalPrice());

        $model->getAdditionalPrice() ? $price += $model->getAdditionalPrice() + $model->getPrice() : $price += $model->getPrice();
        $model->getPercent() ? $price += $price * $model->getPercent() : $price += $price;

        $product->setPrice(ceil($price));
        return $product;
    }

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
        $product = $this->setProductFromModel($product, $model);
        $product->setProductSize($model->getProductSize());

        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(), self::PRODUCT_IMAGE_FOLDER);
            $product->setTitlePhoto($newTitlePhoto);
        }

        return $product;
    }

    private function checkIsCurrentDate(\DateTimeInterface $orderDate): bool
    {
        $currentDate = new \DateTime('today');

        if ($currentDate->format('Y-m-d') !== $orderDate->format('Y-m-d')) {
            return false;
        }

        return true;
    }
}