<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ApiResource]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $phone_number = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Langue $langue = null;


    public function __construct()
    {
        $this->contactHasMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * @return Collection<int, ContactHasMessage>
     */
    public function getContactHasMessages(): Collection
    {
        return $this->contactHasMessages;
    }

    public function addContactHasMessage(ContactHasMessage $contactHasMessage): static
    {
        if (!$this->contactHasMessages->contains($contactHasMessage)) {
            $this->contactHasMessages->add($contactHasMessage);
            $contactHasMessage->setContact($this);
        }

        return $this;
    }

    public function removeContactHasMessage(ContactHasMessage $contactHasMessage): static
    {
        if ($this->contactHasMessages->removeElement($contactHasMessage)) {
            // set the owning side to null (unless already changed)
            if ($contactHasMessage->getContact() === $this) {
                $contactHasMessage->setContact(null);
            }
        }

        return $this;
    }

    public function getLangue(): ?Langue
    {
        return $this->langue;
    }

    public function setLangue(?Langue $langue): static
    {
        $this->langue = $langue;

        return $this;
    }
}
