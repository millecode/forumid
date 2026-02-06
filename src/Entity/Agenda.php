<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: AgendaRepository::class)]
#[ORM\Table(name: 'agenda')] 
class Agenda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'agendas')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "Veuillez choisir une date (EventDate).")]
    private ?EventDate $eventDate = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Assert\NotNull(message: "Veuillez renseigner l'heure de début.")]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Assert\NotNull(message: "Veuillez renseigner l'heure de fin.")]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: "Veuillez renseigner la catégorie.")]
    #[Assert\Length(max: 60, maxMessage: "Catégorie trop longue (max {{ limit }}).")]
    private ?string $category = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "Veuillez renseigner le titre.")]
    #[Assert\Length(max: 180, maxMessage: "Titre trop long (max {{ limit }}).")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    #[Assert\Callback]
    public function validateTimes(ExecutionContextInterface $context): void
    {
        if ($this->startTime && $this->endTime && $this->startTime > $this->endTime) {
            $context->buildViolation("L'heure de début doit être inférieure ou égale à l'heure de fin.")
                ->atPath('endTime')
                ->addViolation();
        }
    }

    public function getId(): ?int { return $this->id; }

    public function getEventDate(): ?EventDate { return $this->eventDate; }
    public function setEventDate(?EventDate $eventDate): self { $this->eventDate = $eventDate; return $this; }

    public function getStartTime(): ?\DateTimeImmutable { return $this->startTime; }
    public function setStartTime(?\DateTimeImmutable $startTime): self { $this->startTime = $startTime; return $this; }

    public function getEndTime(): ?\DateTimeImmutable { return $this->endTime; }
    public function setEndTime(?\DateTimeImmutable $endTime): self { $this->endTime = $endTime; return $this; }

    public function getCategory(): ?string { return $this->category; }
    public function setCategory(?string $category): self { $this->category = $category; return $this; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): self { $this->title = $title; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
