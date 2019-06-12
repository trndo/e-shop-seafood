<?php

namespace App\Model;
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


}