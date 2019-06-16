<?php

namespace App\Model;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ProductModel
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
     * @Assert\Length(max="400", maxMessage="Длина описания не должна быть ольше 400 символов")
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
     * @Assert\Count(
     *      max = 3,
     *      maxMessage = "Вы можете загрузить максимум {{ limit }} фото"
     * )
     * @var array|UploadedFile|null $photo
     */
    private $photo;


    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return ProductModel
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmountPerUnit(): ?string
    {
        return $this->amountPerUnit;
    }

    /**
     * @param $amountPerUnit
     * @return ProductModel
     */
    public function setAmountPerUnit(?string $amountPerUnit): self
    {
        $this->amountPerUnit = $amountPerUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeightPerUnit(): ?string
    {
        return $this->weightPerUnit;
    }

    /**
     * @param $weightPerUnit
     * @return ProductModel
     */
    public function setWeightPerUnit(?string $weightPerUnit): self
    {
        $this->weightPerUnit = $weightPerUnit;

        return $this;
    }


    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return ProductModel
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @param $unit
     * @return ProductModel
     */
    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param $price
     * @return ProductModel
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return ProductModel
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    /**
     * @param $seoTitle
     * @return ProductModel
     */
    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    /**
     * @param $seoDescription
     * @return ProductModel
     */
    public function setSeoDescription(?string $seoDescription): self
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getTitlePhoto(): ?UploadedFile
    {
        return $this->titlePhoto;
    }

    /**
     * @param $titlePhoto
     * @return ProductModel
     */
    public function setTitlePhoto(?UploadedFile $titlePhoto): self
    {
        $this->titlePhoto = $titlePhoto;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductSize(): ?string
    {
        return $this->productSize;
    }

    /**
     * @param $productSize
     * @return ProductModel
     */
    public function setProductSize(?string $productSize): self
    {
        $this->productSize = $productSize;

        return $this;
    }


    /**
     * @return array|null
     */
    public function getPhoto(): ?array
    {
        return $this->photo;
    }


    /**
     * @param UploadedFile|null $photo
     * @return ProductModel
     */
    public function setPhoto($photo): self
    {
        if($photo instanceof UploadedFile) {
            $this->photo[] = $photo;
        }
        else {
            $this->photo = $photo;
        }
        return $this;
    }


}