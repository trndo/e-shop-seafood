<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderDetailsRepository")
 */
class OrderDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="orderDetails", cascade={"persist", "remove"})
     */
    private $product;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Receipt", mappedBy="orderDetails", cascade={"persist", "remove"})
     */
    private $receipt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderInfo", inversedBy="orderDetails")
     */
    private $orderInfo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
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

        // set (or unset) the owning side of the relation if necessary
        $newOrderDetails = $receipt === null ? null : $this;
        if ($newOrderDetails !== $receipt->getOrderDetails()) {
            $receipt->setOrderDetails($newOrderDetails);
        }

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
