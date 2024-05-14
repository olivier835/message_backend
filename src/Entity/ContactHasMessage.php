<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ContactHasMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactHasMessageRepository::class)]
#[ApiResource]
class ContactHasMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contactHasMessages')]
    private ?Contact $contact = null;

    #[ORM\ManyToOne(inversedBy: 'contactHasMessages')]
    private ?Message $Message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->Message;
    }

    public function setMessage(?Message $Message): static
    {
        $this->Message = $Message;

        return $this;
    }
}
