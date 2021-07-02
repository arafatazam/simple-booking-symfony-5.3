<?php

namespace App\Controller;

use App\Contract\VacancyManagerInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/api/vacancy")
 */
class VacancyController extends AbstractController
{
    private VacancyManagerInterface $vm;

    public function __construct(VacancyManagerInterface $vm)
    {
        $this->vm = $vm;
    }

    /**
     * @Route("/{date}", name="vacancy.get", methods={"GET"})
     * @ParamConverter("date", options={"format": "!Y-m-d"})
     */
    public function read(DateTime $date): Response
    {
        $vacancy = $this->vm->read($date);
        return $this->json($vacancy);
    }
}
