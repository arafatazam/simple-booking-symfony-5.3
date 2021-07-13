<?php

namespace App\DTO;

use DateTimeInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationRequest
{
    /**
     * @JMS\Type("DateTime<'!Y-m-d'>")
     * @Assert\NotBlank
     */
    public DateTimeInterface $startDate;

    /**
     * @JMS\Type("DateTime<'!Y-m-d'>")
     * @Assert\NotBlank
     */
    public DateTimeInterface $endDate;


    /**
     * Get the value of startDate
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate
     *
     * @return  self
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the value of endDate
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}
