<?php


namespace App\Model;


use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReceiptModel
{
    /**
     * @Assert\NotBlank(message="Это поле не должно быть пустым")
     * @var string|null $name
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Это поле не должно быть пустым")
     * @var string|null $unit
     */
    private $unit;

    /**
     * @Assert\NotBlank(message="Это поле не должно быть пустым")
     * @Assert\Range(min="0" , maxMessage="Цена не может быть меньше {{ limit }}")
     * @Assert\Type("numeric", message="Тут должны быть цифры")
     * @var float|null $price
     */
    private $price;

    /**
     * @Assert\NotBlank(message="Это поле не должно быть пустым")
     * @Assert\Length(max="400", maxMessage="Длина описания не должна быть больше 400 символов")
     * @var string|null $description
     */
    private $description;

    /**
     * @var string|null $seoTitle
     */
    private $seoTitle;

    /**
     * @var string|null $seoDescription
     */
    private $seoDescription;

    /**
     * @Assert\File(
     *     maxSize = "10m",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Неправильный тип данных",
     *     maxSizeMessage="Масмальный размер файла 10мб"
     * )
     * @var UploadedFile|null $titlePhoto
     */
    private $titlePhoto;

    /**
     * @var  string|null $productSize
     */
    private $productSize;

    /**
     * @var string|null $amountPerUnit
     */
    private $amountPerUnit;

    /**
     * @var string|null $weightPerUnit
     */
    private $weightPerUnit;

    /**
     * @Assert\NotBlank(message="Это поле не должно быть пустым")
     * @var Category|null $category
     */
    private $category;

    /**
     * @Assert\All({
     * @Assert\File(
     *     maxSize = "10m",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Неправильный тип данных",
     *     maxSizeMessage= "Масмальный размер файла 10мб"
     * )
     * })
     * @var array|UploadedFile|null $photo
     */
    private $photo;

    /**
     * @var Product
     */
    private $product;

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
}