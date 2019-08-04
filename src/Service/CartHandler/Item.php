<?php

namespace App\Service\CartHandler;

class Item
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $itemType
     */
    private $itemType;

    /**
     * @var float $quantity
     */
    private $quantity;

    /**
     * @var int|null $relatedProductId
     */
    private $relatedProductId = null;

    /**
     * @var bool
     */
    private $valid = true;

    /** @var string */
    private $invalidMessage = '';

    /**
     * @var float $rest
     */
    private $rest = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Item
     */
    public function setId(int $id): Item
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemType(): string
    {
        return $this->itemType;
    }

    /**
     * @param string $itemType
     * @return Item
     */
    public function setItemType(string $itemType): Item
    {
        $this->itemType = $itemType;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return Item
     */
    public function setQuantity(float $quantity): Item
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRelatedProductId(): ?int
    {
        return $this->relatedProductId;
    }

    /**
     * @param int|null $relatedProductId
     * @return Item
     */
    public function setRelatedProductId(?int $relatedProductId): Item
    {
        $this->relatedProductId = $relatedProductId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     * @return Item
     */
    public function setValid(bool $valid): Item
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvalidMessage(): string
    {
        return $this->invalidMessage;
    }

    /**
     * @param string $invalidMessage
     * @return Item
     */
    public function setInvalidMessage(string $invalidMessage): Item
    {
        $this->invalidMessage = $invalidMessage;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return [
            'status' => $this->isValid(),
            'message' => $this->getInvalidMessage(),
            'rest' => $this->getRest()
        ];
    }

    /**
     * @return string
     */
    public function getUniqueIndex():string
    {
        return $this->getItemType() == 'receipt'
            ? $this->getItemType().'-'.$this->getId().'-'.$this->getRelatedProductId()
            : $this->getItemType().'-'.$this->getId();
    }

    /**
     * @return float
     */
    public function getRest(): ?float
    {
        return $this->rest;
    }

    /**
     * @param float $rest
     * @return Item
     */
    public function setRest(float $rest): Item
    {
        $this->rest = $rest;
        return $this;
    }


}