<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setUsername($faker->name());
            $user->setPassword($this->randomPassword($faker));
            
            $manager->persist($user);
            $this->addReference('user-' . $i, $user);
        }
        
        $manager->flush();
    }
    
    /**
     * The purpose of this method is to generate a random hash for the password column of the User entity.
     * The password itself doesn't matter.
     */
    private function randomPassword(\Faker\Generator $faker): string
    {
        // https://github.com/symfony/password-hasher
        $factory = new PasswordHasherFactory(['common' => ['algorithm' => 'bcrypt']]);
        $passwordHasher = $factory->getPasswordHasher('common');
        $hash = $passwordHasher->hash($faker->password(8)); // returns a bcrypt hash
        return $hash;
    }
}
