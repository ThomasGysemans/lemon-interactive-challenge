<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixture extends Fixture
{
    // this constant is very important,
    // as it also controls the amount of references we can use in `./EventFixtures.php`.
    public const NUMBER_OF_USERS = 4; // 3 +1 to count the known User

    /**
     * This is the password of the generated user we can log in as.
     * What's nice about this user is that we can reload the fixtures
     * and still have a user to log in with and do the tests that require authentication.
     */
    private const KNOWN_USER_PASSWORD = "123456Aa#";
    
    /**
     * The email of the generated user we can log in with.
     */
    private const KNOWN_USER_EMAIL = "gysemansthomas@gmail.com";

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        for ($i = 1; $i <= UserFixture::NUMBER_OF_USERS - 1; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setUsername($faker->name());
            $user->setPassword($this->hashPassword($faker));
            
            $manager->persist($user);
            $this->addReference('user-' . $i, $user);
        }

        $knownUser = new User();
        $knownUser->setEmail(UserFixture::KNOWN_USER_EMAIL);
        $knownUser->setUsername($faker->userName());
        $knownUser->setPassword($this->hashPassword(null, UserFixture::KNOWN_USER_PASSWORD));
        $manager->persist($knownUser);
        $this->addReference('user-' . UserFixture::NUMBER_OF_USERS, $knownUser);
        
        $manager->flush();
    }
    
    /**
     * The purpose of this method is to generate a random hash for the password column of the User entity.
     * The password itself doesn't matter.
     */
    private function hashPassword(?\Faker\Generator $faker, ?string $password = null): string
    {
        // https://github.com/symfony/password-hasher
        $factory = new PasswordHasherFactory(['common' => ['algorithm' => 'bcrypt']]);
        $passwordHasher = $factory->getPasswordHasher('common');
        $hash = $passwordHasher->hash(is_null($password) ? $faker->password(8) : $password);
        return $hash;
    }
}
