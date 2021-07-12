<?php

namespace App\Controller;

use App\Contract\VacancyManagerInterface;
use App\DTO\VacancyRequest;
use DateTime;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/vacancy")
 */
class VacancyController extends AbstractController
{
    private VacancyManagerInterface $vacancyManager;

    private SerializerInterface $serializer;

    public function __construct(VacancyManagerInterface $vm, SerializerInterface $serializer)
    {
        $this->vacancyManager = $vm;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="vacancy.create", methods={"POST"})
     */
    public function create(VacancyRequest $vacancy): Response
    {
        $this->vacancyManager->create($vacancy);
        return $this->json([
            'success' => true
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{date}", name="vacancy.get", methods={"GET"})
     * @ParamConverter("date", options={"format": "!Y-m-d"})
     */
    public function read(DateTime $date): Response
    {
        $vacancy = $this->vacancyManager->read($date);
        $payload = $this->serializer->serialize($vacancy, 'json');
        return new JsonResponse($payload, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/{start}/{end}", name="vacancy.range", methods={"GET"})
     * @ParamConverter("start", options={"format": "!Y-m-d"})
     * @ParamConverter("end", options={"format": "!Y-m-d"})
     */
    public function rangeRead(DateTime $start, DateTime $end): Response
    {
        $result = $this->vacancyManager->rangeRead($start, $end);
        $payload = $this->serializer->serialize($result, 'json');
        return new JsonResponse($payload, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/{date}", name="vacancy.update", methods={"POST"})
     * @ParamConverter("date", options={"format": "!Y-m-d"})
     */
    public function update(DateTime $date, VacancyRequest $vacancy): Response
    {
        $this->vacancyManager->update($date, $vacancy);
        return $this->json([
            'success' => true
        ], Response::HTTP_OK);
    }
}
