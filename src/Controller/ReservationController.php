<?php

namespace App\Controller;

use App\Contract\ReservationManagerInterface;
use App\DTO\ReservationRequest;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/reservation")
 */
class ReservationController extends AbstractController
{
    private ReservationManagerInterface $reservationManager;

    private SerializerInterface $serializer;

    public function __construct(ReservationManagerInterface $rm, SerializerInterface $sl)
    {
        $this->reservationManager = $rm;
        $this->serializer = $sl;
    }

    /**
     * @Route("", name="reservation.create", methods={"POST"})
     */
    public function reserve(ReservationRequest $r): Response
    {
        $reservation = $this->reservationManager->reserve($r);
        $payload = $this->serializer->serialize($reservation, 'json');
        return new JsonResponse($payload, Response::HTTP_CREATED, [], true);
    }
}
