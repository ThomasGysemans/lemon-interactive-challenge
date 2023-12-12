<?php

namespace App\Controller;

use DateTime;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    const EVENTS_PER_PAGE = 6;

    // The "EventRepository" instance is auto-injected,
    // BUT the Symfony server needs to be restarted
    // because it goes crazy and starts saying it doesn't find... the Link Button class,
    // two things that are obviously related.
    #[Route('/', name: 'index')]
    public function index(
        EventRepository $eventRepository,
        #[MapQueryParameter] ?string $debut,
        #[MapQueryParameter] ?string $fin,
        #[MapQueryParameter] int $page = 0
    ): Response
    {
        $result = [];
        $offset = $page * HomeController::EVENTS_PER_PAGE;
        $max = HomeController::EVENTS_PER_PAGE;

        if ($this->hasQueryParam($debut) && $this->hasQueryParam($fin)) {
            $result = $eventRepository->findBetweenDates(new DateTime($debut), new DateTime($fin), $offset, $max);
        } else if ($this->hasQueryParam($debut)) {
            $result = $eventRepository->findAfterDate(new DateTime($debut), $offset, $max);
        } else if ($this->hasQueryParam($fin)) {
            $result = $eventRepository->findBeforeDate(new DateTime($fin), $offset, $max);
        } else {
            $result = $eventRepository->findPage($offset, $max);
        }

        $events = $result['events'];

        return $this->render('home/index.html.twig', [
            'events' => $events,
            'page' => $page,
            'debut' => $debut,
            'count' => $result['count'], // the total number of events respecting the criteria
            'fin' => $fin,
            'eventsPerPage' => HomeController::EVENTS_PER_PAGE
        ]);
    }

    private function hasQueryParam(?string $param): bool
    {
        return !is_null($param) && !empty($param);
    }
}
