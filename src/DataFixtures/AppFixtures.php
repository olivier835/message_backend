<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Langue;
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
         $message1->setContenu("Bonjour, ceci est un message de test.");
         $message1->setStatut("sent");
        $message1->setTradContenu("draft");
        $message1->setDateMessage(new \DateTime('now'));
         $message1->setSender($user); // Associé à l'utilisateur normal
         $manager->persist($message1);

         $message2 = new Message();
         $message2->setContenu("Ceci est un autre message de test.");
         $message2->setStatut("draft");
         $message2->setTradContenu("draft");
         $message2->setDateMessage(new \DateTime('now'));
         $message2->setSender($userAdmin); // Associé à l'administrateur
         $manager->persist($message2);

          // Création de contacts
        $contact1 = new Contact();
        $contact1->setName("Jean Dupont");
        $contact1->setPhoneNumber(1234567890);
        $manager->persist($contact1);

        $contact2 = new Contact();
        $contact2->setName("Marie Curie");
        $contact2->setPhoneNumber(987654321);
        $manager->persist($contact2);

        // Supposons que Langue est une entité déjà créée et persistée
        $langue1 = new Langue(); // Assurez-vous que ces instances sont correctement créées
        $langue1->setName('Français');
        $langue1->setCode('Fr');
        $manager->persist($langue1);

        $langue2 = new Langue();
        $langue2->setName('Anglais');
        $langue2->setCode('En');
        $manager->persist($langue2);

        // Associer des langues aux contacts
        /*$contact1->addContactHasLangue($langue1);
        $contact1->addContactHasLangue($langue2);

        $contact2->addContactHasLangue($langue2);*/



        $manager->flush();
    }
}
