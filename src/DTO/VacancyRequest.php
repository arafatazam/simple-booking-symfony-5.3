<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use DateTimeInterface;

class VacancyRequest
{
    /**
     * @JMS\Type("DateTimeInterface<'!Y-m-d'>")
     * @Assert\NotBlank
     */
    public DateTimeInterface $date;

    /**
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     */
    public ?int $availableSlots;

    /**
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     */
    public ?float $price;


    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of availableSlots
     */
    public function getAvailableSlots()
    {
        return $this->availableSlots;
    }

    /**
     * Set the value of availableSlots
     *
     * @return  self
     */
    public function setAvailableSlots($availableSlots)
    {
        $this->availableSlots = $availableSlots;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }
}
