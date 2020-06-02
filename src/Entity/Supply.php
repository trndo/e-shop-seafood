<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupplyRepository")
 */
class Supply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="supply", cascade={"persist", "remove"})
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $reservationQuantity;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getReservationQuantity(): ?float
    {
        return $this->reservationQuantity;
    }

    public function setReservationQuantity(?float $reservationQuantity): self
    {
        $this->reservationQuantity = $reservationQuantity;

        return $this;
    }
}
