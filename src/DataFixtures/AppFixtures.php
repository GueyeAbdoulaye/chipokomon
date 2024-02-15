<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Persona;
use App\Entity\Chimpokodex;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * Hasher de mot de passe
     *
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $paramName = "userPasswordHasher";
        $this->faker = Factory::create('fr_FR');
        $this->$paramName = $userPasswordHasher;
    }
    
    /**
     * Load New datas
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $personas = [];
            for ($i=0; $i < 10; $i++) { 
            $gender = random_int( 0, 1);
        $genderStr = $gender ? 'male' : "female";
        $persona = new Persona();
        $birthdateStart =  new \DateTime("01/01/1980");
        $birthdateEnd = new \DateTime("01/01/2000");
        $birthDate = $this->faker->dateTimeBetween($birthdateStart,$birthdateEnd);
           $created = $this->faker->dateTimeBetween("-1 week", "now");
            $updated = $this->faker->dateTimeBetween($created, "now");
        $persona
        ->setPhone($this->faker->e164PhoneNumber())
        ->setGender($gender)
        ->setName($this->faker->lastName($genderStr))
        ->setSurName($this->faker->firstName($genderStr))
        ->setEmail($this->faker->email())
        ->setBirthdate( $birthDate)
        ->setAnonymous(false)
        ->setStatus("on")
        ->setCreatedAt($created)
        ->setUpdatedAt($updated);

        $manager->persist($persona);
        $personas[] = $persona;
        }

        $users = [];

        //Set Public User
        $publicUser = new User();
        $publicUser->setUsername("public");
        $publicUser->setRoles(["PUBLIC"]);
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, "public"));
        $publicUser->setPersona($personas[array_rand($personas, 1)]);
        $manager->persist($publicUser);
        $users[] = $publicUser;


        for ($i = 0; $i < 5; $i++) {
            $userUser = new User();
            $password = $this->faker->password(2, 6);
            $userUser->setUsername($this->faker->userName() . "@". $password);
            $userUser->setRoles(["USER"]);
            $userUser->setPassword($this->userPasswordHasher->hashPassword($userUser, $password));
            $userUser->setPersona($personas[array_rand($personas, 1)]);
            
            $manager->persist($userUser);
            $users[] = $userUser;
        }
        
            // Admins
        $adminUser = new User();
        $adminUser->setUsername("admin");
        $adminUser->setRoles(["ADMIN"]);
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, "password"));
        $adminUser->setPersona($personas[array_rand($personas, 1)]);
        $manager->persist($adminUser);
        $users[] = $adminUser;


        $chimpokodexEntries = [];
        for ($i=0; $i < 100; $i++) { 
            //Instantiate new Chimpokodex Entity to Fullfill
            $chimpokodex = new Chimpokodex();
            //Handle created && updated datetime
            $created = $this->faker->dateTimeBetween("-1 week", "now");
            $updated = $this->faker->dateTimeBetween($created, "now");
            //Asign Properties to Entity
            $chimpokodex
            ->setName($this->faker->word())
            ->setPvMax(100)
            ->setCreatedAt($created)
            ->setUpdatedAt($updated)
            ->setStatus("on");
            
            //stock Chimpokodex Entry
            $chimpokodexEntries[] = $chimpokodex;
            //Add to transaction
            $manager->persist($chimpokodex);
        }

        //Execute transaction 
        foreach ($chimpokodexEntries as $key => $chimpokodexEntry) {
            $evolution = $chimpokodexEntries[array_rand($chimpokodexEntries, 1)];
                $chimpokodexEntry->addEvolution($evolution);
                $manager->persist($chimpokodexEntry); 
        }

        $manager->flush();


    }
}