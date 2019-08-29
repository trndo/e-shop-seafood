<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderDetailRepository")
 */
class OrderDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="orderDetail" )
     */
    private $product;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Receipt", inversedBy="orderDetail" )
     */
    private $receipt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderInfo", inversedBy="orderDetails", cascade={"persist", "remove"})
     */
    private $orderInfo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getOrderInfo(): ?OrderInfo
    {
        return $this->orderInfo;
    }

    public function setOrderInfo(?OrderInfo $orderInfo): self
    {
        $this->orderInfo = $orderInfo;

        return $this;
    }
}
