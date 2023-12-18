<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
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
        EntityManagerInterface $entityManager
    ): Response
    {
        $request = Request::createFromGlobals();
        if ($request->isMethod("POST")) {
            $author = $userRepository->find(intval($request->request->get("author")));
            if (is_null($author)) return $this->goback();
            $title = trim($request->request->get("title"));
            $location = trim($request->request->get("location"));
            $beginDate = new DateTime($request->request->get("beginDate"));
            $endDate = new DateTime($request->request->get("endDate"));
            $description = trim($request->request->get("description"));
            if (strlen($description) > 500) return $this->goback();
            if (strlen($location) > 50) return $this->goback();
            if (strlen($title) > 50) return $this->goback();
            $event = new Event();
            $event->setAuthor($author);
            $event->setTitle($title);
            $event->setLocation($location);
            $event->setBeginDate($beginDate);
            $event->setEndDate($endDate);
            $event->setDescription($description);
            $entityManager->persist($event);
            $entityManager->flush();
        }
        
        return $this->goback();
    }

    private function goback(): RedirectResponse
    {
        return $this->redirectToRoute("index", [
            "_fragment" => "evenements" # an anchor (it adds "#evenements" at the end of the URL)
        ]);
    }
}
