<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // The "EventRepository" instance is auto-injected,
    // BUT the Symfony server needs to be restarted
    // because it goes crazy and starts saying it doesn't find... the Link Button class,
    // two things that are obviously related.
    #[Route('/', name: 'index')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('home/index.html.twig', [
            'events' => $events
        ]);
    }
}
