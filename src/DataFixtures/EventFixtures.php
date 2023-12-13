<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 9; $i++) {
            $event = new Event();
            $event->setTitle($faker->sentence(5));
            $event->setLocation($faker->city());
            $event->setBeginDate($faker->dateTimeBetween('-1 week', '+3 week'));
            $event->setEndDate($faker->dateTimeBetween('+3 week', '+5 week'));
            $event->setDescription($faker->text(500));
            $event->setAuthor($this->getReference('user-' . rand(1, UserFixture::NUMBER_OF_USERS))); // apparently the max value is included
            $manager->persist($event);
        }

        $manager->flush();
    }
    
    // the fixtures to load before this one,
    // because this fixture uses references created in them
    public function getDependencies(): array
    {
        return [
            UserFixture::class
        ];
    }
}
