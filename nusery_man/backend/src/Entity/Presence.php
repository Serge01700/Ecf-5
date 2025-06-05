<?php


namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PresenceRepository::class)]
#[ORM\Table(name: 'presences')]
#[ORM\UniqueConstraint(name: 'unique_child_date', columns: ['child_id', 'date'])]
class Presence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Child::class, inversedBy: 'presences')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'L\'enfant est obligatoire')]
    private ?Child $child = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotNull(message: 'La date est obligatoire')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'decimal', precision: 4, scale: 2)]
    #[Assert\NotNull(message: 'Le nombre d\'heures est obligatoire')]
    #[Assert\Range(min: 0.25, max: 12, notInRangeMessage: 'Le nombre d\'heures doit être entre 0.25 et 12')]
    private ?float $hours = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $arrivalTime = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $departureTime = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChild(): ?Child
    {
        return $this->child;
    }

    public function setChild(?Child $child): self
    {
        $this->child = $child;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getHours(): ?float
    {
        return $this->hours;
    }

    public function setHours(float $hours): self
    {
        $this->hours = $hours;
        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(?\DateTimeInterface $arrivalTime): self
    {
        $this->arrivalTime = $arrivalTime;
        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(?\DateTimeInterface $departureTime): self
    {
        $this->departureTime = $departureTime;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }
}