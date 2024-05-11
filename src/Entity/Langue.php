<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LangueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LangueRepository::class)]
#[ApiResource]
class Langue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 4)]
    private ?string $code = null;

    /**
     * @var Collection<int, ContactHasLangue>
     */
    #[ORM\OneToMany(targetEntity: ContactHasLangue::class, mappedBy: 'langue')]
    private Collection $contactHasLangues;

    public function __construct()
    {
        $this->contactHasLangues = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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
            $contactHasLangue->setLangue($this);
        }

        return $this;
    }

    public function removeContactHasLangue(ContactHasLangue $contactHasLangue): static
    {
        if ($this->contactHasLangues->removeElement($contactHasLangue)) {
            // set the owning side to null (unless already changed)
            if ($contactHasLangue->getLangue() === $this) {
                $contactHasLangue->setLangue(null);
            }
        }

        return $this;
    }
}
