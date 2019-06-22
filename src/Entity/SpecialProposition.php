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
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="specialProposition")
     */
    private $gift;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $globalPrice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="specialProposition")
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Receipt", mappedBy="specialReceipt")
     */
    private $receipt;

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

    public function __construct()
    {
        $this->gift = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->receipt = new ArrayCollection();
    }

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

    /**
     * @return Collection|Product[]
     */
    public function getGift(): Collection
    {
        return $this->gift;
    }

    public function addGift(Product $gift): self
    {
        if (!$this->gift->contains($gift)) {
            $this->gift[] = $gift;
            $gift->setSpecialProposition($this);
        }

        return $this;
    }

    public function removeGift(Product $gift): self
    {
        if ($this->gift->contains($gift)) {
            $this->gift->removeElement($gift);
            // set the owning side to null (unless already changed)
            if ($gift->getSpecialProposition() === $this) {
                $gift->setSpecialProposition(null);
            }
        }

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

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setSpecialProposition($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getSpecialProposition() === $this) {
                $product->setSpecialProposition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Receipt[]
     */
    public function getReceipt(): Collection
    {
        return $this->receipt;
    }

    public function addReceipt(Receipt $receipt): self
    {
        if (!$this->receipt->contains($receipt)) {
            $this->receipt[] = $receipt;
            $receipt->setSpecialReceipt($this);
        }

        return $this;
    }

    public function removeReceipt(Receipt $receipt): self
    {
        if ($this->receipt->contains($receipt)) {
            $this->receipt->removeElement($receipt);
            // set the owning side to null (unless already changed)
            if ($receipt->getSpecialReceipt() === $this) {
                $receipt->setSpecialReceipt(null);
            }
        }

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
}
