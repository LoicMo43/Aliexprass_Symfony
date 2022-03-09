<?php

namespace App\Controller\Calendar;

use App\Repository\CalendarRepository;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    /**
     * @throws JsonException
     */
    #[Route('/planning', name: 'planning')]
    public function index(CalendarRepository $calendar): Response
    {
        $events = $calendar->findAll();
        $rdvs = [];

        foreach($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay()
            ];
        }
        $data = json_encode($rdvs, JSON_THROW_ON_ERROR);
        return $this->render(
            'planning/index.html.twig', compact('data'));
    }
}
