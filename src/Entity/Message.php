<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(
)]

class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contentMessage = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $scheduleDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $scheduleTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateMessage = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: 'message')]
    private Collection $recipient;

    public function __construct()
    {
        $this->recipient = new ArrayCollection();
    }

    



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentMessage(): ?string
    {
        return $this->contentMessage;
    }

    public function setContentMessage(string $contentMessage): static
    {
        $this->contentMessage = $contentMessage;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getScheduleDate(): ?\DateTimeInterface
    {
        return $this->scheduleDate;
    }

    public function setScheduleDate(?\DateTimeInterface $scheduleDate): static
    {
        $this->scheduleDate = $scheduleDate;

        return $this;
    }

    public function getScheduleTime(): ?\DateTimeInterface
    {
        return $this->scheduleTime;
    }

    public function setScheduleTime(?\DateTimeInterface $scheduleTime): static
    {
        $this->scheduleTime = $scheduleTime;

        return $this;
    }

    public function getDateMessage(): ?\DateTimeInterface
    {
        return $this->dateMessage;
    }

    public function setDateMessage(\DateTimeInterface $dateMessage): static
    {
        $this->dateMessage = $dateMessage;

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
     * @return Collection<int, Contact>
     */
    public function getRecipient(): Collection
    {
        return $this->recipient;
    }

    public function addRecipient(Contact $recipient): static
    {
        if (!$this->recipient->contains($recipient)) {
            $this->recipient->add($recipient);
        }

        return $this;
    }

    public function removeRecipient(Contact $recipient): static
    {
        $this->recipient->removeElement($recipient);

        return $this;
    }

    
  
    
}
