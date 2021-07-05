<?php

namespace App\Service;

use App\Contract\ReservationManagerInterface;
use App\DTO\ReservationRequest;
use App\DTO\VacancyRequest;
use App\Entity\Reservation;
use App\Entity\Vacancy;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VacancyRepository;
use LogicException;

class ReservationManager implements ReservationManagerInterface
{
    private EntityManagerInterface $em;

    const NO_SLOTS = 'Slots unavailable between the date range';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function reserve(ReservationRequest $resReq): Reservation
    {
        $start = $resReq->getStartDate()->getTimestamp();
        $end = $resReq->getEndDate()->getTimestamp();

        /**@var VacancyRepository */
        $vr = $this->em->getRepository(Vacancy::class);
        $qry = $vr->createQueryBuilder('v')
            ->where('v.date >= :start')
            ->setParameter('start', $start)
            ->andWhere('v.date <= :end')
            ->setParameter('end', $end)
            ->getQuery();

        $vv = $qry->execute();
        $daysBetween = $resReq->getEndDate()->diff($resReq->getStartDate())->format('%a')+1;
        if (count($vv) != $daysBetween) {
            throw new LogicException(self::NO_SLOTS);
        }
        $bookingPrice = 0;

        /**@var Vacancy */
        foreach ($vv as $vnc) {
            if ($vnc->getBookedSlots() >= $vnc->getAvailableSlots()) {
                throw new LogicException(self::NO_SLOTS);
            }
            $bookingPrice += $vnc->getPrice();
            $vnc->setBookedSlots($vnc->getBookedSlots() + 1);
        }

        $res = new Reservation();
        $res->setStartDate($resReq->getStartDate())
            ->setEndDate($resReq->getEndDate())
            ->setBookingPrice($bookingPrice);
        $this->em->persist($res);
        $this->em->flush();
        return $res;
    }
}
