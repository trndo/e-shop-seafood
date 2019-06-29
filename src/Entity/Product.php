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
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="product")
     */
    private $photos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titlePhoto;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrderDetails", mappedBy="product", cascade={"persist", "remove"})
     */
    private $orderDetails;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\SpecialProposition", inversedBy="gift")
     */
    private $specialProposition;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="products")
     */
    private $additionalProduct;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="additionalProduct")
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


    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->receipts = new ArrayCollection();
        $this->rating = 0;
        $this->status = 0;
        $this->additionalProduct = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->receiptSales = new ArrayCollection();
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

    public function getOrderDetails(): ?OrderDetails
    {
        return $this->orderDetails;
    }

    public function setOrderDetails(?OrderDetails $orderDetails): self
    {
        $this->orderDetails = $orderDetails;

        // set (or unset) the owning side of the relation if necessary
        $newProduct = $orderDetails === null ? null : $this;
        if ($newProduct !== $orderDetails->getProduct()) {
            $orderDetails->setProduct($newProduct);
        }

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

    public function getSpecialProposition(): ?SpecialProposition
    {
        return $this->specialProposition;
    }

    public function setSpecialProposition(?SpecialProposition $specialProposition): self
    {
        $this->specialProposition = $specialProposition;

        return $this;
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
    public function removeLikedPractice(Receipt $receiptSale): self
    {
        if ($this->receiptSales->contains($receiptSale)) {
            $this->receiptSales->removeElement($receiptSale);
        }

        return $this;
    }
}
