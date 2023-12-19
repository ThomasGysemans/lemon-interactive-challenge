<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsSubmissionsController extends AbstractController
{
    #[Route('/events_submissions', name: 'post_events_submissions')]
    public function index(
        UserRepository $userRepository,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $request = Request::createFromGlobals();
        if ($request->isMethod("POST")) {
            $isDelete = $request->request->has("delete");
            if ($isDelete) {
                $this->handleEventDeletion($request, $eventRepository);
            } else {
                if ($this->isModificationOfEvent($request)) {
                    return $this->handleEventModification($request, $eventRepository, $entityManager);
                } else {
                    return $this->handleEventCreation($request, $userRepository, $entityManager);
                }
            }
        }
        
        return $this->goback();
    }

    private function readInformationOfRequest(Request $request): array
    {
        
        $data = [
            "title" => trim($request->request->get("title")),
            "location" => trim($request->request->get("location")),
            "beginDate" => new DateTime($request->request->get("beginDate")),
            "endDate" => new DateTime($request->request->get("endDate")),
            "description" => trim($request->request->get("description")),
        ];
        if (strlen($data["description"]) > 500) return $this->goback();
        if (strlen($data["location"]) > 50) return $this->goback();
        if (strlen($data["title"]) > 50) return $this->goback();
        return $data;
    }

    private function populateEventObject(Event $event, array $data): void
    {
        $event->setTitle($data["title"]);
        $event->setLocation($data["location"]);
        $event->setBeginDate($data["beginDate"]);
        $event->setEndDate($data["endDate"]);
        $event->setDescription($data["description"]);
    }

    private function handleEventCreation(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $data = $this->readInformationOfRequest($request);
        if (is_null($data)) return $this->goback();
        $author = $userRepository->find(intval($request->request->get("author")));
        if (is_null($author)) return $this->goback();
        $event = new Event();
        $event->setAuthor($author);
        $this->populateEventObject($event, $data);
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->goback();
    }

    private function handleEventModification(Request $request, EventRepository $eventRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $data = $this->readInformationOfRequest($request);
        if (is_null($data)) return $this->goback();
        $event = $eventRepository->find(intval($request->request->get("event")));
        if (is_null($event)) return $this->goback();
        $this->populateEventObject($event, $data);
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->goback();
    }
    
    private function handleEventDeletion(Request $request, EventRepository $eventRepository): void
    {
        $eventRepository->deleteEvent(intval($request->request->get("event")));
    }

    private function isModificationOfEvent(Request $request): bool
    {
        return $request->request->has("event") && !empty($request->request->get("event"));
    }

    private function goback(): RedirectResponse
    {
        return $this->redirectToRoute("index", [
            "_fragment" => "evenements" # an anchor (it adds "#evenements" at the end of the URL)
        ]);
    }
}
