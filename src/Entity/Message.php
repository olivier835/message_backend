<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $trad_contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $schedule_date = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $sender = null;

    /**
     * @var Collection<int, ContactHasMessage>
     */
    #[ORM\OneToMany(targetEntity: ContactHasMessage::class, mappedBy: 'Message')]
    private Collection $contactHasMessages;

    public function __construct()
    {
        $this->contactHasMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getTradContenu(): ?string
    {
        return $this->trad_contenu;
    }

    public function setTradContenu(string $trad_contenu): static
    {
        $this->trad_contenu = $trad_contenu;

        return $this;
    }

    public function getDateMessage(): ?\DateTimeInterface
    {
        return $this->date_message;
    }

    public function setDateMessage(\DateTimeInterface $date_message): static
    {
        $this->date_message = $date_message;

        return $this;
    }

    public function getScheduleDate(): ?\DateTimeInterface
    {
        return $this->schedule_date;
    }

    public function setScheduleDate(\DateTimeInterface $schedule_date): static
    {
        $this->schedule_date = $schedule_date;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

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
            $contactHasMessage->setMessage($this);
        }

        return $this;
    }

    public function removeContactHasMessage(ContactHasMessage $contactHasMessage): static
    {
        if ($this->contactHasMessages->removeElement($contactHasMessage)) {
            // set the owning side to null (unless already changed)
            if ($contactHasMessage->getMessage() === $this) {
                $contactHasMessage->setMessage(null);
            }
        }

        return $this;
    }
}
