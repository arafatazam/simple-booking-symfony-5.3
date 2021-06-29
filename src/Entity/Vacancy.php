<?php

namespace App\Entity;

use App\Repository\VacancyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VacancyRepository::class)
 */
class Vacancy
{
    /**
     * @ORM\Id
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $availableSlots;

    /**
     * @ORM\Column(type="integer")
     */
    private $bookedSlots=0;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        if(is_null($this->date)){
            $this->date = $date;
        }
        return $this;
    }

    public function getAvailableSlots(): ?int
    {
        return $this->availableSlots;
    }

    public function setAvailableSlots(int $availableSlots): self
    {
        $this->availableSlots = $availableSlots;

        return $this;
    }

    public function getBookedSlots(): ?int
    {
        return $this->bookedSlots;
    }

    public function setBookedSlots(int $bookedSlots): self
    {
        $this->bookedSlots = $bookedSlots;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
