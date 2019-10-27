<?php

namespace App\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryModel
{
    /**
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @var string $name
     */
    private $name;

    /**
     * @var string $seoTitle
     */
    private $seoTitle;

    /**
     * @var string $seoDescription
     */
    private $seoDescription;

    /**
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @var string|null $type
     */
    private $type;

    /**
     * @var string|null $displayType
     */
    private $displayType = 'simple';

    /**
     * @var UploadedFile|null
     */
    private $titlePhoto;

    /**
     * @var string|null
     */
    private $initialCardText;

    /**
     * @var string|null
     */
    private $seoText;

    /**
     * @return UploadedFile|null
     */
    public function getTitlePhoto(): ?UploadedFile
    {
        return $this->titlePhoto;
    }

    /**
     * @param UploadedFile|null $titlePhoto
     * @return CategoryModel
     */
    public function setTitlePhoto(?UploadedFile $titlePhoto): self
    {
        $this->titlePhoto = $titlePhoto;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplayType(): ?string
    {
        return $this->displayType;
    }

    /**
     * @param string|null $displayType
     * @return CategoryModel
     */
    public function setDisplayType(?string $displayType): CategoryModel
    {
        $this->displayType = $displayType;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return CategoryModel
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

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
     * @return CategoryModel
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    /**
     * @param string|null $seoTitle
     * @return CategoryModel
     */
    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    /**
     * @param string|null $seoDescription
     * @return CategoryModel
     */
    public function setSeoDescription(?string $seoDescription): self
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInitialCardText(): ?string
    {
        return $this->initialCardText;
    }

    /**
     * @param string|null $initialCardText
     * @return CategoryModel
     */
    public function setInitialCardText(?string $initialCardText): CategoryModel
    {
        $this->initialCardText = $initialCardText;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoText(): ?string
    {
        return $this->seoText;
    }

    /**
     * @param string|null $seoText
     */
    public function setSeoText(?string $seoText): void
    {
        $this->seoText = $seoText;
    }

}