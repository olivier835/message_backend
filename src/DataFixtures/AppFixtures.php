<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function load(ObjectManager $manager): void
    {
       
        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@bookapi.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@bookapi.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);
        // $product = new Product();
        // $manager->persist($product);
         // Création des messages
         $message1 = new Message();
         $message1->setContentMessage("Bonjour, ceci est un message de test.");
         $message1->setStatus("sent");
         $message1->setDateMessage(new \DateTime('now'));
         $message1->setSender($user); // Associé à l'utilisateur normal
         $manager->persist($message1);
 
         $message2 = new Message();
         $message2->setContentMessage("Ceci est un autre message de test.");
         $message2->setStatus("draft");
         $message2->setDateMessage(new \DateTime('now'));
         $message2->setSender($userAdmin); // Associé à l'administrateur
         $manager->persist($message2);
 

        $manager->flush();
    }
}
