<?php

namespace App\Contract;

use App\DTO\VacancyRequest;
use App\Entity\Vacancy;
use DateTimeInterface;

interface VacancyManagerInterface
{
    public function create(VacancyRequest $vr);
    public function read(DateTimeInterface $date): Vacancy;
    public function update(VacancyRequest $vr);
}
