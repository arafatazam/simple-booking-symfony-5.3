<?php

namespace App\Service;

use App\Contract\VacancyManagerInterface;
use App\DTO\VacancyRequest;
use App\Entity\Vacancy;
use App\Repository\VacancyRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VacancyManager implements VacancyManagerInterface
{
    const NOT_FOUND_MSG = 'No vacancy found on this date.';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(VacancyRequest $vr)
    {
        $vacancy = new Vacancy;
        $vacancy->setDate($vr->getDate())
            ->setAvailableSlots($vr->getAvailableSlots())
            ->setPrice($vr->getPrice());
        $this->em->persist($vacancy);
        $this->em->flush();
    }

    public function read(DateTimeInterface $date): Vacancy
    {
        $repo = $this->em->getRepository(Vacancy::class);
        $vacancy = $repo->findOneBy(['date' => $date->getTimestamp()]);
        if (is_null($vacancy)) {
            throw new NotFoundHttpException(self::NOT_FOUND_MSG);
        }
        return $vacancy;
    }

    public function update(DateTimeInterface $date, VacancyRequest $vr)
    {
        if ($date != $vr->getDate()) {
            throw new BadRequestHttpException('Cannot update date');
        }
        $repo = $this->em->getRepository(Vacancy::class);
        /**@var Vacancy */
        $vacancy = $repo->findOneBy(['date' => $vr->getDate()->getTimestamp()]);
        if (is_null($vacancy)) {
            throw new NotFoundHttpException(self::NOT_FOUND_MSG);
        }
        if (!is_null($vr->getAvailableSlots())) {
            if ($vr->getAvailableSlots() < $vacancy->getBookedSlots()) {
                throw new LogicException("Cannot update slots to {$vr->getAvailableSlots()}.\
                Already {$vacancy->getBookedSlots()} slots have been booked.");
            }
            $vacancy->setAvailableSlots($vr->getAvailableSlots());
        }
        if (!is_null($vr->getPrice())) {
            $vacancy->setPrice($vr->getPrice());
        }
        $this->em->flush();
    }

    public function rangeRead(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $st = $start->getTimestamp();
        $et = $end->getTimestamp() + 1;
        /**@var VacancyRepository */
        $repo = $this->em->getRepository(Vacancy::class);
        $query = $repo->createQueryBuilder('v')
            ->where('v.date BETWEEN :st AND :et')
            ->setParameter('st', $st)
            ->setParameter('et', $et)
            ->getQuery();
        $result = $query->execute();
        return $result;
    }
}
