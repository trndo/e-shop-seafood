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
     * @Assert\Length(max="170", maxMessage="Длина дескрипшина не должна быть больше 170 символов")
     * @var string|null $seoDescription
     */
    private $seoDescription;

    /**
     * @Assert\File(
     *     maxSize = "30m",
     *     mimeTypes = {"image/jpeg", "image/png", "video/mp4", "video/mpeg", "video/webm"},
     *     mimeTypesMessage = "Неправильный тип данных",
     *     maxSizeMessage="Масмальный размер файла 30мб"
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
     * @var float|null
     */
    private $percent;

    /**
     * @var float|null
     */
    private $additionalPrice;

    /**
     * @var bool|null
     */
    private $extraHot;

    /**
     * @var bool|null
     */
    private $extraAlcohol;

    /**
     * @return bool|null
     */
    public function getExtraHot(): ?bool
    {
        return $this->extraHot;
    }

    /**
     * @param bool|null $extraHot
     * @return ReceiptModel
     */
    public function setExtraHot(?bool $extraHot): ReceiptModel
    {
        $this->extraHot = $extraHot;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getExtraAlcohol(): ?bool
    {
        return $this->extraAlcohol;
    }

    /**
     * @param bool|null $extraAlcohol
     * @return ReceiptModel
     */
    public function setExtraAlcohol(?bool $extraAlcohol): ReceiptModel
    {
        $this->extraAlcohol = $extraAlcohol;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercent(): ?float
    {
        return $this->percent;
    }

    /**
     * @param float|null $percent
     * @return ReceiptModel
     */
    public function setPercent(?float $percent): ReceiptModel
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAdditionalPrice(): ?float
    {
        return $this->additionalPrice;
    }

    /**
     * @param float|null $additionalPrice
     * @return ReceiptModel
     */
    public function setAdditionalPrice(?float $additionalPrice): ReceiptModel
    {
        $this->additionalPrice = $additionalPrice;
        return $this;
    }

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
        if ($photo instanceof UploadedFile) {
            $this->photo[] = $photo;
        } else {
            $this->photo = $photo;
        }
        return $this;
    }

}