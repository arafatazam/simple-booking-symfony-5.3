<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @JMS\Type("DateTimeInterface<'Y-m-d'>")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     * @JMS\Type("DateTimeInterface<'Y-m-d'>")
     */
    private $endDate;

    /**
     * @ORM\Column(type="float")
     */
    private $bookingPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getBookingPrice(): ?float
    {
        return $this->bookingPrice;
    }

    public function setBookingPrice(float $bookingPrice): self
    {
        $this->bookingPrice = $bookingPrice;

        return $this;
    }
}
