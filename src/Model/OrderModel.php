<?php


namespace App\Model;


use App\Entity\OrderDetail;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class OrderModel
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $surname;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $phoneNumber;

    /**
     * @var \DateTime|null
     */
    private $orderDate;

    /**
     * @var \DateTime|null
     */
    private $orderTime;

    /**
     * @var string|null
     */
    private $deliveryType;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var int|null
     */
    private $totalPrice;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $coordinates;

    /**
     * @var Collection|null
     */
    private $orderDetails;

    /**
     * @var string|null
     */
    private $comment;


    /**
     * @return Collection|null
     */
    public function getOrderDetails(): ?Collection
    {
        return $this->orderDetails;
    }

    /**
     *
     * @param Collection|null $orderDetails
     * @return OrderModel
     */
    public function setOrderDetails(?Collection $orderDetails): self
    {
        $this->orderDetails = $orderDetails;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return OrderModel
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    /**
     * @param string|null $coordinates
     * @return OrderModel
     */
    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return OrderModel
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;


    }

    /**
     * @param string|null $surname
     * @return OrderModel
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return OrderModel
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     * @return OrderModel
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    /**
     * @param string|null $deliveryType
     * @return OrderModel
     */
    public function setDeliveryType(?string $deliveryType): self
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return OrderModel
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    /**
     * @param int|null $totalPrice
     * @return OrderModel
     */
    public function setTotalPrice(?int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getOrderDate(): ?\DateTime
    {
        return $this->orderDate;
    }

    /**
     * @param \DateTime|null $orderDate
     * @return OrderModel
     */
    public function setOrderDate(?\DateTime $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getOrderTime(): ?\DateTime
    {
        return $this->orderTime;
    }

    /**
     * @param \DateTime|null $orderTime
     * @return OrderModel
     */
    public function setOrderTime(?\DateTime $orderTime): self
    {
        $this->orderTime = $orderTime;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }


}