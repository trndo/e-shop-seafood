<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="product", cascade={"remove"})
     */
    private $photos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titlePhoto;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Receipt", mappedBy="products")
     */
    private $receipts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Supply", mappedBy="product", cascade={"persist", "remove"})
     */
    private $supply;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $productSize;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $originalProduct;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $amountPerUnit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $weightPerUnit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="products", cascade={"persist", "remove"})
     */
    private $additionalProduct;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="additionalProduct", cascade={"persist", "remove"})
     */
    private $products;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Receipt", inversedBy="productSales")
     * @ORM\JoinTable(name="product_receipt_sales",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="receipt_id", referencedColumnName="id")}
     * )
     */
    private $receiptSales;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpecialProposition", mappedBy="product", cascade={"persist", "remove"})
     */
    private $specialPropositions;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SpecialProposition", mappedBy="gift", cascade={"persist", "remove"})
     */
    private $gift;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrderDetail", mappedBy="product", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="product", referencedColumnName="id", onDelete="SET NULL")
     */
    private $orderDetail;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="product", cascade={"persist", "remove"})
     */
    private $reservations;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $additionPrice;


    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->receipts = new ArrayCollection();
        $this->additionalProduct = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->receiptSales = new ArrayCollection();
        $this->specialPropositions = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->rating = 0;
        $this->status = 0;
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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

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
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

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

    /**
     * @return Collection|Receipt[]
     */
    public function getReceipts(): Collection
    {
        return $this->receipts;
    }

    public function addReceipt(Receipt $receipt): self
    {
        if (!$this->receipts->contains($receipt)) {
            $this->receipts[] = $receipt;
            $receipt->addProduct($this);
        }

        return $this;
    }

    public function removeReceipt(Receipt $receipt): self
    {
        if ($this->receipts->contains($receipt)) {
            $this->receipts->removeElement($receipt);
            $receipt->removeProduct($this);
        }

        return $this;
    }

    public function getSupply(): ?Supply
    {
        return $this->supply;
    }

    public function setSupply(?Supply $supply): self
    {
        $this->supply = $supply;

        // set (or unset) the owning side of the relation if necessary
        $newProduct = $supply === null ? null : $this;
        if ($newProduct !== $supply->getProduct()) {
            $supply->setProduct($newProduct);
        }

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

    public function getOriginalProduct(): ?bool
    {
        return $this->originalProduct;
    }

    public function setOriginalProduct(?bool $originalProduct): self
    {
        $this->originalProduct = $originalProduct;

        return $this;
    }

    public function getAmountPerUnit(): ?string
    {
        return $this->amountPerUnit;
    }

    public function setAmountPerUnit(?string $amountPerUnit): self
    {
        $this->amountPerUnit = $amountPerUnit;

        return $this;
    }

    public function getWeightPerUnit(): ?string
    {
        return $this->weightPerUnit;
    }

    public function setWeightPerUnit(?string $weightPerUnit): self
    {
        $this->weightPerUnit = $weightPerUnit;

        return $this;
    }

    public function getTitlePhotoPath(): ?string
    {
        return '/uploads/products/'.$this->getTitlePhoto();
    }

    public function getDataForRating(): string
    {
        return 'product_'.$this->getId();
    }

    public function getType(): string
    {
        return 'product';
    }

    /**
     * @return Collection|self[]
     */
    public function getAdditionalProduct(): Collection
    {
        return $this->additionalProduct;
    }

    public function addAdditionalProduct(self $additionalProduct): self
    {
        if (!$this->additionalProduct->contains($additionalProduct)) {
            $this->additionalProduct[] = $additionalProduct;
        }

        return $this;
    }

    public function removeAdditionalProduct(self $additionalProduct): self
    {
        if ($this->additionalProduct->contains($additionalProduct)) {
            $this->additionalProduct->removeElement($additionalProduct);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(self $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addAdditionalProduct($this);
        }

        return $this;
    }

    public function removeProduct(self $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeAdditionalProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Receipt[]
     */
    public function getReceiptSales(): Collection
    {
        return $this->receiptSales;
    }

    /**
     * @param Receipt $receiptSale
     *
     * @return Product
     */
    public function addReceiptSale(Receipt $receiptSale): self
    {
        if (!$this->receiptSales->contains($receiptSale)) {
            $this->receiptSales[] = $receiptSale;
        }

        return $this;
    }

    /**
     * @param Receipt $receiptSale
     *
     * @return Product
     */
    public function removeReceiptSales(Receipt $receiptSale): self
    {
        if ($this->receiptSales->contains($receiptSale)) {
            $this->receiptSales->removeElement($receiptSale);
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
            $specialProposition->setProduct($this);
        }

        return $this;
    }

    public function removeSpecialProposition(SpecialProposition $specialProposition): self
    {
        if ($this->specialPropositions->contains($specialProposition)) {
            $this->specialPropositions->removeElement($specialProposition);
            // set the owning side to null (unless already changed)
            if ($specialProposition->getProduct() === $this) {
                $specialProposition->setProduct(null);
            }
        }

        return $this;

    }

    public function getGift(): ?SpecialProposition
    {
        return $this->gift;
    }

    public function setGift(?SpecialProposition $specialProposition): self
    {
        $this->gift = $specialProposition;

        // set (or unset) the owning side of the relation if necessary
        $newGift = $specialProposition === null ? null : $this;
        if ($newGift !== $specialProposition->getGift()) {
            $specialProposition->setGift($newGift);
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
        $newProduct = $orderDetail === null ? null : $this;
        if ($newProduct !== $orderDetail->getProduct()) {
            $orderDetail->setProduct($newProduct);
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setProduct($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getProduct() === $this) {
                $reservation->setProduct(null);
            }
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

    public function getAdditionPrice(): ?float
    {
        return $this->additionPrice;
    }

    public function setAdditionPrice(float $additionPrice): self
    {
        $this->additionPrice = $additionPrice;

        return $this;
    }
}
