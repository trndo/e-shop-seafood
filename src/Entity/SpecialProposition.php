<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialPropositionRepository")
 */
class SpecialProposition
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
    private $specialPrice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $percent;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $globalPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $availableAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productSize;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Receipt", inversedBy="specialPropositions")
     */
    private $receipt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="specialPropositions")
     */
    private $product;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="gift", cascade={"persist", "remove"})
     */
    private $gift;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialPrice(): ?float
    {
        return $this->specialPrice;
    }

    public function setSpecialPrice(?float $specialPrice): self
    {
        $this->specialPrice = $specialPrice;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(?int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }


    public function getGlobalPrice(): ?int
    {
        return $this->globalPrice;
    }

    public function setGlobalPrice(?int $globalPrice): self
    {
        $this->globalPrice = $globalPrice;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAvailableAt(): ?\DateTimeInterface
    {
        return $this->availableAt;
    }

    public function setAvailableAt(?\DateTimeInterface $availableAt): self
    {
        $this->availableAt = $availableAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProductSize(): ?string
    {
        return $this->productSize;
    }

    public function setProductSize(?string $productSize): self
    {
        $this->productSize = $productSize;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getGift(): ?Product
    {
        return $this->gift;
    }

    public function setGift(?Product $gift): self
    {
        $this->gift = $gift;

        return $this;
    }
}
