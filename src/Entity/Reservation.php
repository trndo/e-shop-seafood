<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="reservations")
     */
    private $product;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reservationTime;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $reservationQuantity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uniqId;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $positionKey;

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

    public function getReservationTime(): ?\DateTimeInterface
    {
        return $this->reservationTime;
    }

    public function setReservationTime(?\DateTimeInterface $reservationTime): self
    {
        $this->reservationTime = $reservationTime;

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

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(?\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getUniqId(): ?string
    {
        return $this->uniqId;
    }

    public function setUniqId(?string $uniqId): self
    {
        $this->uniqId = $uniqId;

        return $this;
    }

    public function getPositionKey(): ?string
    {
        return $this->positionKey;
    }

    public function setPositionKey(?string $positionKey): self
    {
        $this->positionKey = $positionKey;

        return $this;
    }
}
