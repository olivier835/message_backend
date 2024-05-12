<?php

namespace App\DataFixtures;

use App\Entity\ContactHasLangue;
use App\Repository\ContactRepository;
use App\Repository\LangueRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactHasLangueFixtures extends Fixture
{

    private $langueRepository;
    private $contactRepository;
    public function __construct(private LangueRepository $_langueRepository,
    private ContactRepository $_contactRepository){
        $this->langueRepository = $_langueRepository;
        $this->contactRepository = $_contactRepository;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $langues = $this->_langueRepository->findAll();
        $contacts = $this->_contactRepository->findAll();

        $contactLangues = [];
        foreach ($langues as $langue){
            foreach ($contacts as $contact){
                $contactLangues[] = new ContactHasLangue($contact, $langue);
            }
        }
        $manager->persist($contactLangues);
    }
}
