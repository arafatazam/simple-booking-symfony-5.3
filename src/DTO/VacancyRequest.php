<?php

namespace App\DTO;

use DateTimeInterface;

class VacancyRequest
{
    public DateTimeInterface $date;

    public ?int $availableSlots;

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
