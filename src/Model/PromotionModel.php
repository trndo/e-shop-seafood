<?php


namespace App\Model;


use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;

class PromotionModel
{
    /**
     * @var float|null
     */
    private $specialPrice;

    /**
     * @var int|null
     */
    private $percent;

    /**
     * @var Product|null
     */
    private $gift;

    /**
     * @var int|null
     */
    private $globalPrice;

    /**
     * @var Product|null
     */
    private $product;

    /**
     * @var Receipt|null
     */
    private $receipt;

    /**
     * @var float|null
     */
    private $quantity;

    /**
     * @var \DateTime|null
     */
    private $availableAt;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $productSize;

    /**
     * @var Category|null
     */
    private $category;

    /**
     * @var Category|null
     */
    private $giftCategory;

    /**
     * @return Category|null
     */
    public function getGiftCategory(): ?Category
    {
        return $this->giftCategory;
    }

    /**
     * @param Category|null $giftCategory
     * @return PromotionModel
     */
    public function setGiftCategory(?Category $giftCategory): self
    {
        $this->giftCategory = $giftCategory;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return PromotionModel
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductSize(): ?string
    {
        return $this->productSize;
    }


    /**
     * @param string|null $productSize
     * @return PromotionModel
     */
    public function setProductSize(?string $productSize): self
    {
        $this->productSize = $productSize;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getSpecialPrice(): ?float
    {
        return $this->specialPrice;
    }

    /**
     * @param float|null $specialPrice
     * @return PromotionModel
     */
    public function setSpecialPrice(?float $specialPrice): self
    {
        $this->specialPrice = $specialPrice;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPercent(): ?int
    {
        return $this->percent;
    }

    /**
     * @param int|null $percent
     * @return PromotionModel
     */
    public function setPercent(?int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getGift(): ?Product
    {
        return $this->gift;
    }

    /**
     * @
     * @param Product|null $gift
     * @return PromotionModel
     */
    public function setGift(?Product $gift): self
    {
        $this->gift = $gift;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGlobalPrice(): ?int
    {
        return $this->globalPrice;
    }

    /**
     * @param int|null $globalPrice
     * @return PromotionModel
     */
    public function setGlobalPrice(?int $globalPrice): self
    {
        $this->globalPrice = $globalPrice;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return PromotionModel
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Receipt|null
     */
    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    /**
     * @param Receipt|null $receipt
     * @return PromotionModel
     */
    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     * @return PromotionModel
     */
    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getAvailableAt(): ?\DateTime
    {
        return $this->availableAt;
    }

    /**
     * @param \DateTime|null $availableAt
     * @return PromotionModel
     */
    public function setAvailableAt(?\DateTime $availableAt): self
    {
        $this->availableAt = $availableAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return PromotionModel
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


}