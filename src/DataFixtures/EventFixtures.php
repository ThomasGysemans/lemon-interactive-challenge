<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 9; $i++) {
            $event = new Event();
            $event->setTitle("Event " . $i);
            $event->setLocation("Lille");
            $event->setBeginDate(new DateTime('now'));
            $event->setEndDate(new DateTime('now'));
            $event->setDescription("Un super événement ça va être un truc de fou furieux attention vous êtes pas prêts!!Un super événement ça va être un truc de fou furieux attention vous êtes pas prêts!!Un super événement ça va être un truc de fou furieux attention vous êtes pas prêts!!Un super événement ça va être un truc de fou furieux attention vous êtes pas prêts!!Un super événement ça va être un truc de fou furieux attention vous êtes pas prêts!!Un super événement ça va être un truc de fou furieux attention vous êtes pas prêts");
            $manager->persist($event);
        }

        $manager->flush();
    }
}
