<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionsController extends AbstractController
{
    #[Route('/subscriptions', name: 'post_subscriptions')]
    public function index(
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $request = Request::createFromGlobals();
        if ($request->getMethod() === "POST") {
            $user = $this->getUserEntity();
            $event = $eventRepository->find($request->request->get('event'));
            $registered = $request->request->get('registered') === "true";
            if ($registered) {
                // the user must be removed from the subscribers list of the given event
                // and the user must remove the event from its own list of events
                $user->removeEvent($event);
                $event->removeSubscriber($user);
            } else {
                // Same here but we add the event/user
                $user->addEvent($event);
                $event->addSubscriber($user);
            }
            $entityManager->persist($user);
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute("index", [
            "_fragment" => "evenements" # an anchor (it adds "#evenements" at the end of the URL)
        ]);
    }

    private function getUserEntity(): ?User
    {
        return $this->getUser();
    }
}
