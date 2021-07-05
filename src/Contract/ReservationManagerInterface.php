<?php

namespace App\Contract;

use App\DTO\ReservationRequest;
use App\Entity\Reservation;

interface ReservationManagerInterface
{
    public function reserve(ReservationRequest $reservationRequest): Reservation;
}
