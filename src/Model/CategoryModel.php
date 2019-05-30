<?php

namespace App\Model;

class CategoryModel
{
    private $name;

    private $seoTitle;

    private $seoDescription;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CategoryModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }


    /**
     * @param string $seoTitle
     * @return CategoryModel
     */
    public function setSeoTitle(string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }


    /**
     * @param string $seoDescription
     * @return CategoryModel
     */
    public function setSeoDescription(string $seoDescription): self
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }


}