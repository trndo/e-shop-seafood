<?php


namespace App\Model;


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



}