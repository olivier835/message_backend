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

    /**
     * @var Collection<int, ContactHasLangue>
     */
    #[ORM\OneToMany(targetEntity: ContactHasLangue::class, mappedBy: 'contact', orphanRemoval: true)]
    private Collection $contactHasLangues;

    /**
     * @var Collection<int, ContactHasMessage>
     */
    #[ORM\OneToMany(targetEntity: ContactHasMessage::class, mappedBy: 'contact')]
    private Collection $contactHasMessages;

    public function __construct()
    {
        $this->contactHasLangues = new ArrayCollection();
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
     * @return Collection<int, ContactHasLangue>
     */
    public function getContactHasLangues(): Collection
    {
        return $this->contactHasLangues;
    }

    public function addContactHasLangue(ContactHasLangue $contactHasLangue): static
    {
        if (!$this->contactHasLangues->contains($contactHasLangue)) {
            $this->contactHasLangues->add($contactHasLangue);
            $contactHasLangue->setContact($this);
        }

        return $this;
    }

    public function removeContactHasLangue(ContactHasLangue $contactHasLangue): static
    {
        if ($this->contactHasLangues->removeElement($contactHasLangue)) {
            // set the owning side to null (unless already changed)
            if ($contactHasLangue->getContact() === $this) {
                $contactHasLangue->setContact(null);
            }
        }

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
}
