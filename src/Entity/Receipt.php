<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceiptRepository")
 */
class Receipt
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $unit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="receipts")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $seoTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $seoDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titlePhoto;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="receipts")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="receipt", cascade={"remove"})
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="receiptSales")
     */
    private $productSales;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpecialProposition", mappedBy="receipt",cascade={"persist", "remove"})
     */
    private $specialPropositions;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrderDetail", mappedBy="receipt", cascade={"persist", "remove"})
     */
    private $orderDetail;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $additionalPrice;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDeletable;



    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->photo = new ArrayCollection();
        $this->rating = 0;
        $this->status = 0;
        $this->productSales = new ArrayCollection();
        $this->specialPropositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): self
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getTitlePhoto(): ?string
    {
        return $this->titlePhoto;
    }

    public function setTitlePhoto(?string $titlePhoto): self
    {
        $this->titlePhoto = $titlePhoto;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setReceipt($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->contains($photo)) {
            $this->photo->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getReceipt() === $this) {
                $photo->setReceipt(null);
            }
        }

        return $this;
    }


    public function getTitlePhotoPath(): ?string
    {
        return '/uploads/receipts/'.$this->getTitlePhoto();
    }

    public function getFullFilePath(): ?string
    {
        return '/uploads/receipts/'.$this->getTitlePhoto();
    }

    public function getDataForRating(): string
    {
        return 'receipt_'.$this->getId();
    }

    public function getType(): string
    {
        return 'receipt';
    }

    /**
     * @return Collection|Product[]
     */
    public function getProductSales(): Collection
    {
        return $this->productSales;
    }

    /**
     * @param Product $productSale
     *
     * @return Receipt
     */
    public function addProductSale(Product $productSale): self
    {
        if (!$this->productSales->contains($productSale)) {
            $productSale->addReceiptSale($this);
            $this->productSales[] = $productSale;
        }

        return $this;
    }

    /**
     * @param Product $productSale
     *
     * @return Receipt
     */
    public function removeProductSale(Product $productSale): self
    {
        if ($this->productSales->contains($productSale)) {
            $productSale->removeReceiptSales($this);
            $this->productSales->removeElement($productSale);
        }

        return $this;
    }

    public function expose()
    {
        return get_object_vars($this);
    }

    /**
     * @return Collection|SpecialProposition[]
     */
    public function getSpecialPropositions(): Collection
    {
        return $this->specialPropositions;
    }

    public function addSpecialProposition(SpecialProposition $specialProposition): self
    {
        if (!$this->specialPropositions->contains($specialProposition)) {
            $this->specialPropositions[] = $specialProposition;
            $specialProposition->setReceipt($this);
        }

        return $this;
    }

    public function removeSpecialProposition(SpecialProposition $specialProposition): self
    {
        if ($this->specialPropositions->contains($specialProposition)) {
            $this->specialPropositions->removeElement($specialProposition);
            // set the owning side to null (unless already changed)
            if ($specialProposition->getReceipt() === $this) {
                $specialProposition->setReceipt(null);
            }
        }

        return $this;
    }

    public function getOrderDetail(): ?OrderDetail
    {
        return $this->orderDetail;
    }

    public function setOrderDetail(?OrderDetail $orderDetail): self
    {
        $this->orderDetail = $orderDetail;

        // set (or unset) the owning side of the relation if necessary
        $newReceipt = $orderDetail === null ? null : $this;
        if ($newReceipt !== $orderDetail->getReceipt()) {
            $orderDetail->setReceipt($newReceipt);
        }

        return $this;
    }

    public function getPercent(): ?float
    {
        return $this->percent;
    }

    public function setPercent(?float $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    public function getAdditionalPrice(): ?float
    {
        return $this->additionalPrice;
    }

    public function setAdditionalPrice(?float $additionalPrice): self
    {
        $this->additionalPrice = $additionalPrice;

        return $this;
    }

    public function getIsDeletable(): ?bool
    {
        return $this->isDeletable;
    }

    public function setIsDeletable(?bool $isDeletable): self
    {
        $this->isDeletable = $isDeletable;

        return $this;
    }
}
