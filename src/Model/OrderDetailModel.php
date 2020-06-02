<?php


namespace App\Model;


use App\Entity\OrderInfo;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\Supply;

class OrderDetailModel
{
    /**
     * @var float|null
     */
    private $quantity;

    /**
     * @var Product|null
     */
    private $product;

    /**
     * @var Receipt|null
     */
    private $receipt;

    /**
     * @var OrderInfo|null
     */
    private $orderInfo;

    /**
     * @var Supply|null
     */
    private $supply;

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     * @return OrderDetailModel
     */
    public function setQuantity(?float $quantity): OrderDetailModel
    {
        $this->quantity = $quantity;
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
     * @return OrderDetailModel
     */
    public function setProduct(?Product $product): OrderDetailModel
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
     * @return OrderDetailModel
     */
    public function setReceipt(?Receipt $receipt): OrderDetailModel
    {
        $this->receipt = $receipt;
        return $this;
    }

    /**
     * @return OrderInfo|null
     */
    public function getOrderInfo(): ?OrderInfo
    {
        return $this->orderInfo;
    }

    /**
     * @param OrderInfo|null $orderInfo
     * @return OrderDetailModel
     */
    public function setOrderInfo(?OrderInfo $orderInfo): OrderDetailModel
    {
        $this->orderInfo = $orderInfo;
        return $this;
    }

    /**
     * @return Supply|null
     */
    public function getSupply(): ?Supply
    {
        return $this->supply;
    }

    /**
     * @param Supply|null $supply
     * @return OrderDetailModel
     */
    public function setSupply(?Supply $supply): OrderDetailModel
    {
        $this->supply = $supply;
        return $this;
    }


}