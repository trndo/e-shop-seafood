<?php

namespace App\Model;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @param Category|null $category
     * @return ReceiptModel
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
     * @param string|null $name
     * @return ReceiptModel
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
     * @param string|null $unit
     * @return ReceiptModel
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
     * @param float|null $price
     * @return ReceiptModel
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
     * @param string|null $description
     * @return ReceiptModel
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
     * @param string|null $seoTitle
     * @return ReceiptModel
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
     * @param string|null $seoDescription
     * @return ReceiptModel
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
     * @param UploadedFile|null $titlePhoto
     * @return ReceiptModel
     */
    public function setTitlePhoto(?UploadedFile $titlePhoto): self
    {
        $this->titlePhoto = $titlePhoto;

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
     * @param $photo
     * @return ReceiptModel
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