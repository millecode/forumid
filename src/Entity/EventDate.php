<?php

namespace App\Entity;

use App\Repository\EventDateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventDateRepository::class)]
#[ORM\Table(name: 'event_date')]
#[Assert\Expression(
    "this.getStartDate() <= this.getEndDate()",
    message: "La date de début doit être antérieure ou égale à la date de fin."
)]
class EventDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull(message: "La date de début est obligatoire.")]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull(message: "La date de fin est obligatoire.")]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isActive = false;

    

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Agenda>
     */
    #[ORM\OneToMany(targetEntity: Agenda::class, mappedBy: 'eventDate')]
    private Collection $agendas;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->agendas = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getStartDate(): ?\DateTimeImmutable { return $this->startDate; }
    public function setStartDate(\DateTimeImmutable $startDate): self { $this->startDate = $startDate; return $this; }

    public function getEndDate(): ?\DateTimeImmutable { return $this->endDate; }
    public function setEndDate(\DateTimeImmutable $endDate): self { $this->endDate = $endDate; return $this; }

    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $isActive): self { $this->isActive = $isActive; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }

    /**
     * @return Collection<int, Agenda>
     */
    public function getAgendas(): Collection
    {
        return $this->agendas;
    }

    public function addAgenda(Agenda $agenda): static
    {
        if (!$this->agendas->contains($agenda)) {
            $this->agendas->add($agenda);
            $agenda->setEventDate($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): static
    {
        if ($this->agendas->removeElement($agenda)) {
            // set the owning side to null (unless already changed)
            if ($agenda->getEventDate() === $this) {
                $agenda->setEventDate(null);
            }
        }

        return $this;
    }
}
